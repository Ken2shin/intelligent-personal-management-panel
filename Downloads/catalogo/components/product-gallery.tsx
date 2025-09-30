"use client"

import { useEffect, useRef, useState } from "react"
import Image from "next/image"
import { Card } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { ShoppingBag, Heart } from "lucide-react"
import { Description } from "@radix-ui/react-toast"

const products = [
  { id: 1, src: "catalogo/IMG-20250925-WA0026.jpg", name: "Eyer Line", price: "C$60.00" },
  { id: 2, src: "/catalogo/IMG-20250925-WA0027.jpg", name: "Bolishi ColorFul Lip Blam", price: "C$80.00" },
  { id: 3, src: "/catalogo/IMG-20250925-WA0028.jpg", name: "Cat Mascara", price: "C$80.00" },
  { id: 4, src: "/catalogo/IMG-20250925-WA0029.jpg", name: "Romantic Rain Lash", price: "C$100.00" },
  { id: 5, src: "/catalogo/IMG-20250925-WA0030.jpg", name: "Wejcome", price: "C$180.00" },
  { id: 6, src: "/catalogo/IMG-20250925-WA0031.jpg", name: "Matte Lip Gloss", price: "C$150.00" },
  { id: 7, src: "/catalogo/IMG-20250925-WA0032.jpg", name: "Cristal Kiss Gloss", price: "C$120.00" },
  { id: 8, src: "/catalogo/IMG-20250925-WA0033.jpg", name: "Peapl Bubble", price: "C$120.00" },
  { id: 9, src: "/catalogo/IMG-20250925-WA0035.jpg", name: "Bearless", price: "C$70.00" },
  { id: 10, src: "/catalogo/IMG-20250925-WA0036.jpg", name: "Matte Lip Stick", price: "C$80.00" },
  { id: 11, src: "/catalogo/IMG-20250925-WA0037.jpg", name: "Lovely Baby Eyebrow", price: "C$100.00" },
  { id: 12, src: "/catalogo/IMG-20250925-WA0038.jpg", name: "Fundation Star River/Base", price: "C$200.00" },
  { id: 13, src: "/catalogo/IMG-20250925-WA0039.jpg", name: "Cleasing Foan / Make Up Remover", price: "C$180.00" },
  { id: 14, src: "/catalogo/IMG-20250925-WA0040.jpg", name: "Bakery Carame", price: "C$300.00" },
  { id: 15, src: "/catalogo/IMG-20250925-WA0041.jpg", name: "Set Brochas Agoddess", price: "C$280.00" },
  { id: 16, src: "/catalogo/IMG-20250925-WA0042.jpg", name: "Sweet Beauty Power Puff/Esponjas", price: "C$120.00" },
  { id: 17, src: "/catalogo/IMG-20250925-WA0043.jpg", name: "Nicotiname/ Efecto Aclarador", price: "C$150.00" },
  { id: 18, src: "/catalogo/IMG-20250925-WA0044.jpg", name: "Countour Palette", price: "C$380.00" },
  { id: 19, src: "/catalogo/IMG-20250925-WA0045.jpg", name: "OH! Colors", price: "C$150.00" },
  { id: 20, src: "/catalogo/IMG-20250925-WA0046.jpg", name: "Cristal Spray", price: "C$160.00" },
  { id: 21, src: "/catalogo/IMG-20250925-WA0047.jpg", name: "Mascarillas", price: "C$60.00" },
  { id: 23, src: "/catalogo/IMG-20250925-WA0049.jpg", name: "3 Colores Cream Blush Rubor", price: "C$140.00" },
  { id: 24, src: "/catalogo/IMG-20250925-WA0050.jpg", name: "Babe Skin", price: "C$60.00" },
  { id: 25, src: "/catalogo/IMG-20250925-WA0051.jpg", name: "Concelear", price: "C$100.00" },
  { id: 26, src: "/catalogo/IMG-20250925-WA0052.jpg", name: "Peapl Bubble", price: "C$120.00" },
  { id: 27, src: "/catalogo/IMG-20250925-WA0053.jpg", name: "Super Girl Polvo Compacto", price: "C$120.00" },
  { id: 28, src: "/catalogo/IMG-20250925-WA0054.jpg", name: "Lip Gloss De Conejitos", price: "C$50.00" },
  { id: 30, src: "/catalogo/IMG-20250925-WA0063.jpg", name: "Lip Liner & Lipstick", price: "C$130.00" },
  { id: 31, src: "/catalogo/IMG-20250925-WA0057.jpg", name: "Lithe Kiss", price: "C$80.00" },
  { id: 32, src: "/catalogo/IMG-20250925-WA0058.jpg", name: "Jelly Makeup Blush", price: "C$80.00" },
  { id: 33, src: "/catalogo/IMG-20250925-WA0059.jpg", name: "Blusher", price: "C$120.00" },
  { id: 35, src: "/catalogo/IMG-20250925-WA0061.jpg", name: "Liquid Highlight", price: "C$120.00" },
  { id: 36, src: "/catalogo/IMG-20250925-WA0062.jpg", name: "Rubor y Corrector En Crema", price: "C$120.00" },
  { id: 38, src: "/catalogo/IMG-20250925-WA0064.jpg", name: "ICE Cream", price: "C$350.00" },
  { id: 39, src: "/catalogo/IMG-20250925-WA0065.jpg", name: "Peachy Blus Cream", price: "C$120.00" },
  { id: 40, src: "/catalogo/IMG-20250925-WA0066.jpg", name: "Lip Oil Changing", price: "C$80.00" },
  { id: 41, src: "/catalogo/IMG-20250925-WA0067.jpg", name: "Liquid Foundation/Base", price: "C$280.00" },
  { id: 42, src: "/catalogo/IMG-20250925-WA0068.jpg", name: "Esponjas", price: "C$140.00 Por Unidad C$35.00"},
//  { id: 43, src: "/catalogo/IMG-20250925-WA0069.jpg", name: "Roberto Cavalli Paradiso", price: "$76.00" },//
  //{ id: 44, src: "/catalogo/IMG-20250925-WA0070.jpg", name: "Zadig & Voltaire This is Her!", price: "$72.00" },
  { id: 45, src: "/catalogo/IMG-20250925-WA0071.jpg", name: "Paletas de 16 Colores", price: "C$280.00" },
]

export function ProductGallery() {
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
      { threshold: 0.1, rootMargin: "50px" },
    )

    return () => observerRef.current?.disconnect()
  }, [])

  useEffect(() => {
    const elements = document.querySelectorAll("[data-product-card]")
    elements.forEach((el) => observerRef.current?.observe(el))

    return () => {
      elements.forEach((el) => observerRef.current?.unobserve(el))
    }
  }, [])

  const handleWhatsAppInquiry = (productName: string, price: string) => {
    const message = encodeURIComponent(`Hola! Estoy interesado en ${productName} (${price}). ¿Está disponible?`)
    window.open(`https://wa.me/50588779185?text=${message}`, "_blank")
  }

  return (
    <section id="gallery" className="py-20 px-4 bg-gradient-to-b from-background via-primary/5 to-background">
      <div className="container mx-auto">
        {/* Section Header */}
        <div className="text-center mb-16">
          <h2 className="text-4xl md:text-6xl font-bold mb-4 text-foreground">Nuestra Colección</h2>
          <p className="text-xl text-muted-foreground max-w-2xl mx-auto">
            Explora nuestra selección exclusiva de cosméticos de las marcas más reconocidas
          </p>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
          {products.map((product, index) => (
            <Card
              key={product.id}
              data-product-card
              data-id={product.id}
              className={`group relative overflow-hidden bg-gradient-to-br from-card via-card to-primary/5 border-border hover:border-primary/50 transition-all duration-500 hover:shadow-2xl hover:shadow-primary/20 ${
                visibleItems.has(product.id) ? "opacity-100 translate-y-0" : "opacity-0 translate-y-10"
              }`}
              style={{ transitionDelay: `${(index % 8) * 100}ms` }}
            >
              {/* Image Container */}
              <div className="relative aspect-square overflow-hidden bg-gradient-to-br from-primary/10 to-accent/10">
                <Image
                  src={product.src || "/placeholder.svg"}
                  alt={product.name}
                  fill
                  className="object-cover transition-transform duration-700 group-hover:scale-110"
                  sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, (max-width: 1280px) 33vw, 25vw"
                />
                {/* Gradient Overlay */}
                <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />

                {/* Hover Actions */}
                <div className="absolute inset-0 flex items-center justify-center gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-4 group-hover:translate-y-0">
                  <Button
                    size="sm"
                    onClick={() => handleWhatsAppInquiry(product.name, product.price)}
                    className="bg-green-500 hover:bg-green-600 text-white shadow-lg"
                  >
                    <ShoppingBag className="h-4 w-4 mr-2" />
                    Consultar
                  </Button>
                  <Button size="sm" variant="secondary" className="shadow-lg">
                    <Heart className="h-4 w-4" />
                  </Button>
                </div>

                {/* Premium Badge */}
                <div className="absolute top-3 right-3 bg-primary/90 backdrop-blur-sm text-primary-foreground px-3 py-1 rounded-full text-xs font-semibold">
                  Premium
                </div>
              </div>

              {/* Product Info */}
              <div className="p-5 space-y-3">
                <h3 className="font-semibold text-lg text-foreground line-clamp-2 min-h-[3.5rem] group-hover:text-primary transition-colors">
                  {product.name}
                </h3>

                <div className="flex items-center justify-between">
                  <div>
                    <p className="text-2xl font-bold text-primary">{product.price}</p>
                    <p className="text-xs text-muted-foreground">Precio especial</p>
                  </div>
                  <div className="text-right">
                    <div className="flex items-center gap-1">
                      <span className="text-yellow-500">★★★★★</span>
                    </div>
                    <p className="text-xs text-muted-foreground mt-1">Original</p>
                  </div>
                </div>

                {/* Quick Info */}
                <div className="pt-3 border-t border-border/50 flex items-center justify-between text-xs text-muted-foreground">
                  <span>✓ Envío rápido</span>
                  <span>✓ Garantizado</span>
                </div>
              </div>
            </Card>
          ))}
        </div>
      </div>
    </section>
  )
}
