import React, { createContext, useContext, useEffect, useState } from 'react'
import Echo from 'laravel-echo'

declare global { interface Window { Pusher: any } }

export const EchoCtx = createContext<Echo | null>(null)

export function EchoProvider({ children }:{ children: React.ReactNode }) {
  const [echo, setEcho] = useState<Echo | null>(null)
  useEffect(() => {
    const instance = new Echo({
      broadcaster: 'reverb',
      key: import.meta.env.VITE_REVERB_APP_KEY || 'local',
      wsHost: import.meta.env.VITE_REVERB_HOST || '127.0.0.1',
      wsPort: Number(import.meta.env.VITE_REVERB_PORT || 8080),
      forceTLS: false,
      enabledTransports: ['ws'],
    } as any)
    setEcho(instance)
    return () => { (instance as any).disconnect() }
  }, [])
  return <EchoCtx.Provider value={echo}>{children}</EchoCtx.Provider>
}

export function useEcho(){ return useContext(EchoCtx) }