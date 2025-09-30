"use client"

import { useEffect, useRef, useState } from "react"

const stats = [
  { value: "Mucha Variedad", label: "Productos Disponibles" },
  { value: "Atencion de Calidad", label: "Clientes Satisfechos" },
  { value: "Productos Increibles", label: "Marcas Premium" },
  { value: "Calidad Percibida", label: "Satisfacción del Cliente" },
]

export function StatsSection() {
  const [isVisible, setIsVisible] = useState(false)
  const sectionRef = useRef<HTMLElement>(null)

  useEffect(() => {
    const observer = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          setIsVisible(true)
        }
      },
      { threshold: 0.3 },
    )

    if (sectionRef.current) {
      observer.observe(sectionRef.current)
    }

    return () => observer.disconnect()
  }, [])

  return (
    <section ref={sectionRef} className="py-24 px-4 bg-gradient-to-br from-primary via-accent to-primary text-white">
      <div className="container mx-auto">
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12">
          {stats.map((stat, index) => (
            <div
              key={index}
              className={`text-center transition-all duration-700 ${
                isVisible ? "opacity-100 translate-y-0" : "opacity-0 translate-y-10"
              }`}
              style={{ transitionDelay: `${index * 150}ms` }}
            >
              <div className="text-4xl md:text-6xl font-bold mb-2">{stat.value}</div>
              <div className="text-sm md:text-base text-white/90">{stat.label}</div>
            </div>
          ))}
        </div>
      </div>
    </section>
  )
}
