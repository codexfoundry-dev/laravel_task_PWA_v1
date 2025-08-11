import React from 'react'

export function ProjectList() {
  return (
    <div>
      <div className="font-semibold mb-2">Projects</div>
      <ul className="space-y-2">
        {[{name:'Home',color:'#60a5fa'},{name:'Work',color:'#f59e0b'}].map((p)=> (
          <li key={p.name} className="flex items-center gap-2">
            <span className="w-3 h-3 rounded-full" style={{ background: p.color }} />
            <span>{p.name}</span>
          </li>
        ))}
      </ul>
    </div>
  )
}