import React, { useEffect } from 'react'

export function Shortcuts({ onNew, onFocusSearch, onGoKanban, onGoCalendar }:{ onNew:()=>void; onFocusSearch:()=>void; onGoKanban:()=>void; onGoCalendar:()=>void }) {
  useEffect(() => {
    let lastG = 0
    function onKey(e: KeyboardEvent) {
      if (e.key === 'n' || e.key === 'N') onNew()
      if (e.key === '/') { e.preventDefault(); onFocusSearch() }
      if (e.key.toLowerCase() === 'g') lastG = Date.now()
      if (e.key.toLowerCase() === 'k' && Date.now() - lastG < 800) onGoKanban()
      if (e.key.toLowerCase() === 'c' && Date.now() - lastG < 800) onGoCalendar()
    }
    window.addEventListener('keydown', onKey)
    return () => window.removeEventListener('keydown', onKey)
  }, [])
  return null
}