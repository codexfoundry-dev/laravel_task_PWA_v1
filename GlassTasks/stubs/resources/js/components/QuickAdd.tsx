import React, { useState } from 'react'

export function QuickAdd() {
  const [text, setText] = useState('')

  async function submit() {
    if (!text.trim()) return
    await fetch('/api/v1/tasks', { method: 'POST', headers:{'Content-Type':'application/json'}, credentials:'include', body: JSON.stringify({ project_id: 1, title: text, status: 'todo', priority: 'med' }) })
    setText('')
  }

  return (
    <div>
      <div className="font-semibold mb-2">Quick Add</div>
      <input value={text} onChange={(e)=>setText(e.target.value)} onKeyDown={(e)=> e.key==='Enter' && submit()} placeholder='Buy milk tomorrow 5pm #home @me !high' className="w-full bg-white/10 border border-white/20 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-white/30" />
    </div>
  )
}