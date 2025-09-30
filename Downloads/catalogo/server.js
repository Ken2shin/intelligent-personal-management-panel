const { createServer } = require("http")
const { parse } = require("url")
const next = require("next")

// Configuración del servidor
const dev = process.env.NODE_ENV !== "production"
const hostname = "localhost"
const port = process.env.PORT || 3001 // Puerto personalizado

// Inicializar Next.js
const app = next({ dev, hostname, port })
const handle = app.getRequestHandler()

app.prepare().then(() => {
  createServer(async (req, res) => {
    try {
      const parsedUrl = parse(req.url, true)
      await handle(req, res, parsedUrl)
    } catch (err) {
      console.error("Error al manejar la solicitud:", err)
      res.statusCode = 500
      res.end("Error interno del servidor")
    }
  }).listen(port, (err) => {
    if (err) throw err
    console.log(`> Servidor listo en http://${hostname}:${port}`)
    console.log(`> Modo: ${dev ? "desarrollo" : "producción"}`)
  })
})
