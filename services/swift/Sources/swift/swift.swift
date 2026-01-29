import Vapor

var env = try Environment.detect()
try LoggingSystem.bootstrap(from: &env)

let app = Application(env)
defer { app.shutdown() }

// =================================================================
// CONFIGURACIÓN DE PRODUCCIÓN
// =================================================================

// 1. Configuración del Puerto
// En Koyeb/Producción, el sistema suele asignar un puerto vía variable de entorno 'PORT'.
// Si no existe (ej. en local), usamos el 8090 para no chocar con Laravel (8000) ni Go (8080).
if let portStr = Environment.get("PORT"), let portInt = Int(portStr) {
    app.http.server.configuration.port = portInt
} else {
    app.http.server.configuration.port = 8090
}

// 2. Configuración de Host
// En producción debemos escuchar en 0.0.0.0 para ser accesibles desde fuera del contenedor.
app.http.server.configuration.hostname = "0.0.0.0"

app.logger.info("🚀 SWIFT SERVICE INICIANDO EN PUERTO: \(app.http.server.configuration.port)")

// =================================================================
// RUTAS Y ENDPOINTS
// =================================================================

// Ruta de Salud (Health Check) - Vital para que Koyeb sepa que el servicio está vivo
app.get("health") { req async -> String in
    return "✅ Swift Service Operativo | Env: \(app.environment.name)"
}

// Grupo de rutas para notificaciones
let notifyGroup = app.grouped("api", "v1", "dispatch")

// Endpoint 1: Notificar a un Usuario Específico
// Laravel llamará aquí: POST /api/v1/dispatch/user
notifyGroup.post("user") { req async throws -> String in
    // Estructura de datos que esperamos recibir de Laravel
    struct UserNotificationInput: Content {
        var userId: String
        var title: String
        var message: String
        var type: String? // Opcional (finanzas, tarea, etc)
    }
    
    let input = try req.content.decode(UserNotificationInput.self)
    
    // Llamamos a tu NotificationManager para procesar la lógica
    try await NotificationManager.shared.notifyUser(
        app: app,
        userID: input.userId,
        title: input.title,
        message: input.message,
        type: input.type ?? "info"
    )
    
    return "Orden procesada para usuario: \(input.userId)"
}

// Endpoint 2: Notificar a Grupo de Usuarios
// Laravel llamará aquí: POST /api/v1/dispatch/group
notifyGroup.post("group") { req async throws -> String in
    struct GroupNotificationInput: Content {
        var userIds: [String]
        var title: String
        var message: String
    }
    
    let input = try req.content.decode(GroupNotificationInput.self)
    
    try await NotificationManager.shared.notifyMultipleUsers(
        app: app,
        userIDs: input.userIds,
        title: input.title,
        message: input.message
    )
    
    return "Orden procesada para \(input.userIds.count) usuarios"
}

// Endpoint 3: Notificación Global (Broadcast)
// Laravel llamará aquí: POST /api/v1/dispatch/broadcast
notifyGroup.post("broadcast") { req async throws -> String in
    struct BroadcastInput: Content {
        var title: String
        var message: String
    }
    let input = try req.content.decode(BroadcastInput.self)
    
    try await NotificationManager.shared.notifyAll(
        app: app,
        title: input.title,
        message: input.message
    )
    
    return "Broadcast global iniciado"
}

// =================================================================
// EJECUCIÓN
// =================================================================

do {
    try app.run()
} catch {
    app.logger.report(error: error)
    throw error
}