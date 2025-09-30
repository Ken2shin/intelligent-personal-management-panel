"use client"

import { useEffect, useState } from "react"
import Image from "next/image"
import { Button } from "@/components/ui/button"
import { MessageCircle, Sparkles } from "lucide-react"

export function HeroSection() {
  const [isVisible, setIsVisible] = useState(false)

  useEffect(() => {
    setIsVisible(true)
  }, [])

  const scrollToGallery = () => {
    document.getElementById("gallery")?.scrollIntoView({ behavior: "smooth" })
  }

  const openWhatsApp = () => {
    window.open("https://wa.me/50588779185", "_blank")
  }

  return (
    <section id="hero" className="relative min-h-screen flex items-center justify-center overflow-hidden pt-20">
      <div className="absolute inset-0 bg-gradient-to-br from-primary/10 via-accent/5 to-secondary gradient-animate" />

      {/* Decorative elements */}
      <div className="absolute inset-0 overflow-hidden">
        <div className="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/5 rounded-full blur-3xl" />
        <div className="absolute bottom-1/4 right-1/4 w-96 h-96 bg-accent/5 rounded-full blur-3xl" />
      </div>

      {/* Content */}
      <div className="relative z-10 container mx-auto px-4 py-20">
        <div className="max-w-5xl mx-auto">
          <div
            className={`transition-all duration-1000 ${
              isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-10"
            }`}
          >
            {/* Badge */}
            <div className="flex justify-center mb-8">
              <div className="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 rounded-full border border-primary/20">
                <Sparkles className="h-4 w-4 text-primary" />
                <span className="text-sm font-medium text-primary">Belleza Exclusiva de Lujo</span>
              </div>
            </div>

            {/* Logo */}
            <div className="mb-8 flex justify-center">
              <div className="relative w-24 h-24 md:w-32 md:h-32 animate-scale-in">
                <Image
                  src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-2qw7gVIm6pGFM8UrOf4ubJBtoPnwkP.png"
                  alt="Logo"
                  fill
                  className="object-contain drop-shadow-2xl rounded-3xl"
                  priority
                />
              </div>
            </div>

            {/* Title */}
            <h1 className="text-5xl md:text-7xl lg:text-8xl font-bold mb-6 text-foreground text-balance leading-tight text-center">
                Cosméticos
              <br />
              <span className="text-transparent bg-clip-text bg-gradient-to-r from-primary via-accent to-primary">
                de Clase Mundial
              </span>
            </h1>

            {/* Subtitle */}
            <p className="text-xl md:text-2xl text-muted-foreground mb-12 max-w-3xl mx-auto text-pretty text-center leading-relaxed">
              Accede a los productos de belleza de marcas premium. Calidad garantizada,
              entrega rápida y atención personalizada.
            </p>

            {/* CTA Buttons */}
            <div className="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
              <Button
                size="lg"
                onClick={openWhatsApp}
                className="bg-green-500 hover:bg-green-600 text-white px-8 py-6 text-lg shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl"
              >
                <MessageCircle className="mr-2 h-5 w-5" />
                Consultar Disponibilidad
              </Button>
              <Button
                size="lg"
                variant="outline"
                onClick={scrollToGallery}
                className="px-8 py-6 text-lg transition-all duration-300 hover:scale-105 border-2 bg-transparent"
              >
                Explorar Catálogo
              </Button>
            </div>

            {/* Trust indicators */}
            <div className="flex flex-wrap justify-center gap-8 text-center">
              <div>
                <div className="text-3xl font-bold text-foreground">100%</div>
                <div className="text-sm text-muted-foreground">Productos Originales</div>
              </div>
              <div className="hidden sm:block w-px bg-border" />
              <div>
                <div className="text-3xl font-bold text-foreground">24/7</div>
                <div className="text-sm text-muted-foreground">Atención al Cliente</div>
              </div>
              <div className="hidden sm:block w-px bg-border" />
              <div>
                <div className="text-3xl font-bold text-foreground">Envío</div>
                <div className="text-sm text-muted-foreground">Rápido y Seguro</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Scroll Indicator */}
      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
        <div className="w-6 h-10 border-2 border-foreground/20 rounded-full flex items-start justify-center p-2">
          <div className="w-1.5 h-3 bg-foreground/40 rounded-full" />
        </div>
      </div>
    </section>
  )
}
