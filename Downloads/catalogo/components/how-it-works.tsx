"use client"

import { useEffect, useRef, useState } from "react"
import { MessageCircle, Search, ShoppingBag, Truck } from "lucide-react"
import { Card } from "@/components/ui/card"

const steps = [
  {
    icon: Search,
    title: "Explora el Catálogo",
    description: "Navega por nuestra amplia selección de  cosméticos de marcas premium.",
  },
  {
    icon: MessageCircle,
    title: "Contacta por WhatsApp",
    description: "Envíanos un mensaje con el producto que te interesa y te asesoramos personalmente.",
  },
  {
    icon: ShoppingBag,
    title: "Confirma tu Pedido",
    description: "Verificamos disponibilidad, precio y coordinamos los detalles de tu compra.",
  },
  {
    icon: Truck,
    title: "Recibe en Casa",
    description: "Empacamos tu producto con cuidado y lo enviamos directamente a tu puerta.",
  },
]

export function HowItWorks() {
  const [visibleItems, setVisibleItems] = useState<Set<number>>(new Set())
  const observerRef = useRef<IntersectionObserver | null>(null)

  useEffect(() => {
    observerRef.current = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            const id = Number(entry.target.getAttribute("data-id"))
            setVisibleItems((prev) => new Set(prev).add(id))
          }
        })
      },
      { threshold: 0.1 },
    )

    return () => observerRef.current?.disconnect()
  }, [])

  useEffect(() => {
    const elements = document.querySelectorAll("[data-step-card]")
    elements.forEach((el) => observerRef.current?.observe(el))

    return () => {
      elements.forEach((el) => observerRef.current?.unobserve(el))
    }
  }, [])

  return (
    <section id="how-it-works" className="py-24 px-4 bg-background">
      <div className="container mx-auto">
        <div className="text-center mb-16 max-w-3xl mx-auto">
          <h2 className="text-4xl md:text-6xl font-bold mb-6 text-foreground text-balance">Cómo Funciona</h2>
          <p className="text-xl text-muted-foreground text-pretty leading-relaxed">
            Comprar tus productos favoritos es fácil y rápido. Sigue estos simples pasos
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative">
          {/* Connection lines for desktop */}
          <div className="hidden lg:block absolute top-20 left-0 right-0 h-0.5 bg-gradient-to-r from-transparent via-border to-transparent" />

          {steps.map((step, index) => {
            const Icon = step.icon
            return (
              <Card
                key={index}
                data-step-card
                data-id={index}
                className={`relative p-8 bg-card border-border hover:border-primary/50 hover:shadow-xl transition-all duration-500 ${
                  visibleItems.has(index) ? "opacity-100 translate-y-0" : "opacity-0 translate-y-10"
                }`}
                style={{ transitionDelay: `${index * 150}ms` }}
              >
                {/* Step number */}
                <div className="absolute -top-4 left-8 w-8 h-8 bg-primary text-primary-foreground rounded-full flex items-center justify-center font-bold text-sm">
                  {index + 1}
                </div>

                <div className="mb-4 inline-flex p-3 bg-primary/10 rounded-xl">
                  <Icon className="h-8 w-8 text-primary" />
                </div>
                <h3 className="text-xl font-bold mb-3 text-foreground">{step.title}</h3>
                <p className="text-muted-foreground leading-relaxed">{step.description}</p>
              </Card>
            )
          })}
        </div>
      </div>
    </section>
  )
}
