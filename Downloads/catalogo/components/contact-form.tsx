"use client"

import type React from "react"

import { useState } from "react"
import { Button } from "@/components/ui/button"
import { Input } from "@/components/ui/input"
import { Textarea } from "@/components/ui/textarea"
import { Card } from "@/components/ui/card"
import { Send } from "lucide-react"

export function ContactForm() {
  const [formData, setFormData] = useState({
    nombre: "",
    telefono: "",
    mensaje: "",
  })

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()

    const whatsappMessage = `Hola! Me gustaría obtener más información.%0A%0A*Nombre:* ${formData.nombre}%0A*Teléfono:* ${formData.telefono}%0A*Mensaje:* ${formData.mensaje}`

    window.open(`https://wa.me/50588779185?text=${whatsappMessage}`, "_blank")

    setFormData({ nombre: "", telefono: "", mensaje: "" })
  }

  return (
    <section id="contact" className="py-24 px-4 bg-secondary/30">
      <div className="container mx-auto max-w-2xl">
        <Card className="p-8 md:p-12 bg-card border-border shadow-xl">
          <div className="text-center mb-8">
            <h2 className="text-3xl md:text-5xl font-bold mb-4 text-foreground text-balance">¿Listo para Comenzar?</h2>
            <p className="text-lg text-muted-foreground leading-relaxed">
              Completa el formulario y nos pondremos en contacto contigo por WhatsApp de inmediato
            </p>
          </div>

          <form onSubmit={handleSubmit} className="space-y-6">
            <div>
              <label htmlFor="nombre" className="block text-sm font-medium mb-2 text-foreground">
                Nombre completo *
              </label>
              <Input
                id="nombre"
                type="text"
                required
                value={formData.nombre}
                onChange={(e) => setFormData({ ...formData, nombre: e.target.value })}
                placeholder="Juan Pérez"
                className="bg-background border-border text-foreground h-12"
              />
            </div>

            <div>
              <label htmlFor="telefono" className="block text-sm font-medium mb-2 text-foreground">
                Teléfono *
              </label>
              <Input
                id="telefono"
                type="tel"
                required
                value={formData.telefono}
                onChange={(e) => setFormData({ ...formData, telefono: e.target.value })}
                placeholder="+505 1234 5678"
                className="bg-background border-border text-foreground h-12"
              />
            </div>

            <div>
              <label htmlFor="mensaje" className="block text-sm font-medium mb-2 text-foreground">
                ¿Qué producto te interesa? *
              </label>
              <Textarea
                id="mensaje"
                required
                value={formData.mensaje}
                onChange={(e) => setFormData({ ...formData, mensaje: e.target.value })}
                placeholder="Estoy interesado en..."
                rows={5}
                className="bg-background border-border text-foreground resize-none"
              />
            </div>

            <Button
              type="submit"
              size="lg"
              className="w-full bg-green-500 hover:bg-green-600 text-white py-6 text-lg transition-all duration-300 hover:scale-105 shadow-lg hover:shadow-xl"
            >
              <Send className="mr-2 h-5 w-5" />
              Enviar Consulta por WhatsApp
            </Button>
          </form>
        </Card>
      </div>
    </section>
  )
}
