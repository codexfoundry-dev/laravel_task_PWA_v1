import React from 'react'
import { createRoot } from 'react-dom/client'
import { createInertiaApp } from '@inertiajs/react'
import './styles.css'
import { QueryClient, QueryClientProvider } from '@tanstack/react-query'
import { registerSW } from './pwa'
import { EchoProvider } from './lib/echo'

const queryClient = new QueryClient()

registerSW()

declare global {
  interface Window { 
    route?: any
  }
}

createInertiaApp({
  resolve: (name) => {
    const pages = import.meta.glob('./Pages/**/*.tsx', { eager: true })
    return (pages[`./Pages/${name}.tsx`] as any).default
  },
  setup({ el, App, props }) {
    const root = createRoot(el)
    root.render(
      <QueryClientProvider client={queryClient}>
        <EchoProvider>
          <App {...props} />
        </EchoProvider>
      </QueryClientProvider>
    )
  },
})