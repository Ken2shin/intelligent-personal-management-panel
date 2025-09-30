"use client"

import { useEffect, useRef, useState } from "react"
import { Shield, Truck, Star, MessageCircle, Package, Award } from "lucide-react"
import { Card } from "@/components/ui/card"

const features = [
  {
    icon: Shield,
    title: "100% Originales",
    description: "Todos nuestros productos son auténticos y verificados directamente con las marcas oficiales.",
  },
  {
    icon: Truck,
    title: "Envío Rápido",
    description: "Entrega express. Recibe tus productos en tiempo récord.",
  },
  {
    icon: Star,
    title: "Marcas Premium",
    description: "Trabajamos con las marcas más mas favorables para el publico.",
  },
  {
    icon: MessageCircle,
    title: "Atención Personalizada",
    description: "Asesoría experta vía WhatsApp para ayudarte a elegir el producto perfecto.",
  },
  {
    icon: Package,
    title: "Empaque de Lujo",
    description: "Cada producto viene en su empaque original sellado.",
  },
 
]

export function FeaturesSection() {
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
    const elements = document.querySelectorAll("[data-feature-card]")
    elements.forEach((el) => observerRef.current?.observe(el))

    return () => {
      elements.forEach((el) => observerRef.current?.unobserve(el))
    }
  }, [])

  return (
    <section id="features" className="py-24 px-4 bg-secondary/30">
      <div className="container mx-auto">
        <div className="text-center mb-16 max-w-3xl mx-auto">
          <h2 className="text-4xl md:text-6xl font-bold mb-6 text-foreground text-balance">¿Por Qué Elegirnos?</h2>
          <p className="text-xl text-muted-foreground text-pretty leading-relaxed">
            Nos especializamos en ofrecer la mejor experiencia de compra de cosméticos de lujo
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {features.map((feature, index) => {
            const Icon = feature.icon
            return (
              <Card
                key={index}
                data-feature-card
                data-id={index}
                className={`p-8 bg-card border-border hover:border-primary/50 hover:shadow-xl transition-all duration-500 ${
                  visibleItems.has(index) ? "opacity-100 translate-y-0" : "opacity-0 translate-y-10"
                }`}
                style={{ transitionDelay: `${index * 100}ms` }}
              >
                <div className="mb-4 inline-flex p-3 bg-primary/10 rounded-xl">
                  <Icon className="h-8 w-8 text-primary" />
                </div>
                <h3 className="text-xl font-bold mb-3 text-foreground">{feature.title}</h3>
                <p className="text-muted-foreground leading-relaxed">{feature.description}</p>
              </Card>
            )
          })}
        </div>
      </div>
    </section>
  )
}
