# 🌸 Catálogo de Perfumes Premium

Landing page profesional para catálogo de perfumes con integración de WhatsApp y diseño moderno estilo SaaS.

## 📋 Características

- ✨ Diseño moderno y profesional estilo SaaS
- 📱 Totalmente responsive (móvil, tablet, desktop)
- 🎨 Animaciones suaves y transiciones elegantes
- 💬 Integración directa con WhatsApp
- 🖼️ Galería de productos con 58 perfumes
- 📝 Formulario de contacto funcional
- 🚀 Optimizado para rendimiento

## 🛠️ Tecnologías Utilizadas

- **Next.js 14** - Framework de React
- **React 19** - Biblioteca de UI
- **TypeScript** - Tipado estático
- **Tailwind CSS v4** - Estilos y diseño
- **Radix UI** - Componentes accesibles
- **Lucide React** - Iconos modernos
- **Node.js** - Servidor personalizado

## 📦 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- **Node.js** (versión 18 o superior)
- **npm** o **pnpm** o **yarn** (gestor de paquetes)

Verifica tu instalación:
\`\`\`bash
node --version
npm --version
\`\`\`

## 🚀 Instalación

### 1. Clonar o descargar el proyecto

Si tienes el proyecto en un ZIP, descomprímelo. Si está en Git:
\`\`\`bash
git clone <url-del-repositorio>
cd catalogo-perfumes-premium
\`\`\`

### 2. Instalar dependencias

Elige uno de los siguientes comandos según tu gestor de paquetes preferido:

**Con npm:**
\`\`\`bash
npm install
\`\`\`

**Con pnpm (recomendado - más rápido):**
\`\`\`bash
pnpm install
\`\`\`

**Con yarn:**
\`\`\`bash
yarn install
\`\`\`

Este comando instalará todas las dependencias necesarias listadas en `package.json`, incluyendo:
- Next.js y React
- Tailwind CSS
- Componentes de UI (Radix UI)
- Iconos (Lucide React)
- Y todas las demás librerías necesarias

## 🎯 Comandos Disponibles

### Modo Desarrollo (con servidor personalizado en puerto 3001)

\`\`\`bash
npm run dev
\`\`\`

Este comando:
- Inicia el servidor de desarrollo en **http://localhost:3001**
- Habilita hot-reload (recarga automática al guardar cambios)
- Muestra errores y warnings en tiempo real

### Modo Desarrollo (puerto por defecto 3000)

\`\`\`bash
npm run dev:default
\`\`\`

### Construir para Producción

\`\`\`bash
npm run build
\`\`\`

Este comando:
- Compila y optimiza el proyecto para producción
- Genera archivos estáticos optimizados
- Minimiza CSS y JavaScript
- Optimiza imágenes

### Iniciar en Producción

\`\`\`bash
npm run start
\`\`\`

Este comando:
- Inicia el servidor en modo producción en **http://localhost:3001**
- Requiere haber ejecutado `npm run build` primero
- Optimizado para máximo rendimiento

### Verificar Código (Linting)

\`\`\`bash
npm run lint
\`\`\`

## 🌐 Acceder a la Aplicación

Una vez iniciado el servidor de desarrollo:

1. Abre tu navegador
2. Ve a: **http://localhost:3001**
3. La landing page se cargará automáticamente

## 📱 Configuración de WhatsApp

El número de WhatsApp está configurado en los componentes. Para cambiarlo:

1. Busca en el código: `+505 82060270`
2. Reemplázalo con tu número (incluye código de país)
3. Formato: `+[código país][número]` (ejemplo: `+50582060270`)

Archivos donde aparece:
- `components/hero-section.tsx`
- `components/contact-form.tsx`
- `components/whatsapp-button.tsx`

## 🎨 Personalización

### Cambiar Puerto del Servidor

Edita `server.js` y cambia la línea:
\`\`\`javascript
const port = process.env.PORT || 3001 // Cambia 3001 por el puerto deseado
\`\`\`

O usa una variable de entorno:
\`\`\`bash
PORT=4000 npm run dev
\`\`\`

### Modificar Colores

Los colores están definidos en `app/globals.css` usando variables CSS:
\`\`\`css
--primary: ...
--accent: ...
--background: ...
\`\`\`

### Agregar/Modificar Productos

Edita el archivo `components/product-gallery.tsx` y modifica el array de productos.

## 📁 Estructura del Proyecto

\`\`\`
catalogo-perfumes-premium/
├── app/                      # Páginas y rutas de Next.js
│   ├── layout.tsx           # Layout principal
│   ├── page.tsx             # Página de inicio
│   └── globals.css          # Estilos globales
├── components/              # Componentes React
│   ├── hero-section.tsx     # Sección hero
│   ├── product-gallery.tsx  # Galería de productos
│   ├── contact-form.tsx     # Formulario de contacto
│   ├── navigation.tsx       # Barra de navegación
│   └── ui/                  # Componentes de UI reutilizables
├── catalogo/                # Imágenes de productos (58 perfumes)
├── public/                  # Archivos estáticos
├── server.js               # Servidor personalizado Node.js
├── package.json            # Dependencias y scripts
├── tsconfig.json           # Configuración TypeScript
├── next.config.mjs         # Configuración Next.js
└── README.md               # Este archivo
\`\`\`

## 🐛 Solución de Problemas

### Error: "Cannot find module 'next'"

\`\`\`bash
rm -rf node_modules package-lock.json
npm install
\`\`\`

### Puerto ya en uso

Si el puerto 3001 está ocupado:
\`\`\`bash
PORT=3002 npm run dev
\`\`\`

### Errores de TypeScript

\`\`\`bash
npm run lint
\`\`\`

### Limpiar caché de Next.js

\`\`\`bash
rm -rf .next
npm run dev
\`\`\`

## 📞 Soporte

Para soporte o consultas:
- WhatsApp: +505 82060270
- Revisa la documentación de Next.js: https://nextjs.org/docs

## 📄 Licencia

Este proyecto es privado y de uso exclusivo.

---

**Desarrollado con ❤️ usando Next.js y React**
