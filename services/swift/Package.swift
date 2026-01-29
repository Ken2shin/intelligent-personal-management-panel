// swift-tools-version: 5.9
import PackageDescription

let package = Package(
    name: "swift-security-bridge",
    platforms: [
       .macOS(.v13)
    ],
    dependencies: [
        // 1. Vapor: Necesario para levantar el servidor en puerto 8090 y recibir JSON de Laravel
        .package(url: "https://github.com/vapor/vapor.git", from: "4.76.0"),
        
        // 2. Swift Crypto: La librería de seguridad que pediste para encriptación
        .package(url: "https://github.com/apple/swift-crypto.git", "1.0.0" ..< "4.0.0"),
    ],
    targets: [
        .executableTarget(
            name: "swift", // Asegúrate de que tu carpeta de código sea 'Sources/swift'
            dependencies: [
                .product(name: "Vapor", package: "vapor"),
                .product(name: "Crypto", package: "swift-crypto"),
            ]
            // Si tu código no está en Sources/swift, descomenta la linea de abajo y pon la ruta:
            // , path: "Sources" 
        ),
    ]
)