import Vapor
import Crypto

var env = try Environment.detect()
try LoggingSystem.bootstrap(from: &env)
let app = Application(env)
defer { app.shutdown() }

// Koyeb asigna un puerto dinámico mediante la variable PORT
let port = Int(Environment.get("PORT") ?? "8090") ?? 8090
app.http.server.configuration.port = port
app.http.server.configuration.hostname = "0.0.0.0" // Necesario para producción

// Endpoint para que Laravel se comunique con Swift
app.post("api", "v1", "security", "verify") { req async throws -> String in
    let manager = NotificationManager.shared
    // Tu lógica de seguridad aquí
    return "✅ Verificación Swift Completada"
}

try app.run()