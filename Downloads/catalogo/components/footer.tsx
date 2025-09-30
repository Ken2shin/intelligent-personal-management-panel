"use client"

import Image from "next/image"
import { MessageCircle, Instagram, Facebook } from "lucide-react"

export function Footer() {
  const openWhatsApp = () => {
    window.open("https://wa.me/50582060270", "_blank")
  }

  return (
    <footer className="bg-foreground text-background py-16 px-4">
      <div className="container mx-auto">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
          {/* Brand */}
          <div>
            <div className="flex items-center gap-3 mb-4">
              <div className="relative w-12 h-12">
                <Image
                  src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/image-2qw7gVIm6pGFM8UrOf4ubJBtoPnwkP.png"
                  alt="Logo"
                  fill
                  className="object-contain brightness-0 invert"
                />
              </div>
              <span className="text-xl font-bold">Perfumes Premium</span>
            </div>
            <p className="text-background/70 leading-relaxed">
              Tu destino para cosméticos de lujo. Calidad garantizada y atención personalizada.
            </p>
          </div>

          {/* Quick Links */}
          <div>
            <h3 className="text-lg font-bold mb-4">Enlaces Rápidos</h3>
            <ul className="space-y-2">
              <li>
                <button
                  onClick={() => document.getElementById("gallery")?.scrollIntoView({ behavior: "smooth" })}
                  className="text-background/70 hover:text-background transition-colors"
                >
                  Catálogo
                </button>
              </li>
              <li>
                <button
                  onClick={() => document.getElementById("features")?.scrollIntoView({ behavior: "smooth" })}
                  className="text-background/70 hover:text-background transition-colors"
                >
                  Beneficios
                </button>
              </li>
              <li>
                <button
                  onClick={() => document.getElementById("how-it-works")?.scrollIntoView({ behavior: "smooth" })}
                  className="text-background/70 hover:text-background transition-colors"
                >
                  Cómo Funciona
                </button>
              </li>
              <li>
                <button
                  onClick={() => document.getElementById("contact")?.scrollIntoView({ behavior: "smooth" })}
                  className="text-background/70 hover:text-background transition-colors"
                >
                  Contacto
                </button>
              </li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h3 className="text-lg font-bold mb-4">Contacto</h3>
            <div className="space-y-3">
              <button
                onClick={openWhatsApp}
                className="flex items-center gap-2 text-background/70 hover:text-background transition-colors"
              >
                <MessageCircle className="h-5 w-5" />
                <span>+505 8877 9185</span>
              </button>
              <div className="flex gap-4 mt-4">
                <button className="p-2 bg-background/10 hover:bg-background/20 rounded-full transition-colors">
                  <Instagram className="h-5 w-5" />
                </button>
                <button className="p-2 bg-background/10 hover:bg-background/20 rounded-full transition-colors">
                  <Facebook className="h-5 w-5" />
                </button>
              </div>
            </div>
          </div>
        </div>

        {/* Bottom */}
        <div className="pt-8 border-t border-background/20 text-center text-background/70 text-sm">
          <p>&copy; {new Date().getFullYear()} Cosmeticos Premium. Todos los derechos reservados.</p>
        </div>
      </div>
    </footer>
  )
}
