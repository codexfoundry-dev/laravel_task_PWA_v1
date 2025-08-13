import React, { useState } from 'react'

export function BellDropdown() {
  const [open, setOpen] = useState(false)

  return (
    <div className="relative">
      <button onClick={()=>setOpen(!open)} className="glass-chip px-3 py-2">🔔</button>
      {open && (
        <div className="absolute right-0 mt-2 w-72 glass-subcard p-3">
          <div className="font-semibold mb-2">Notifications</div>
          <button className="glass-chip px-2 py-1">Enable Web Push</button>
          <button className="glass-chip px-2 py-1 ml-2">Install App</button>
          <div className="mt-3 text-sm text-white/70">No new notifications.</div>
        </div>
      )}
    </div>
  )
}