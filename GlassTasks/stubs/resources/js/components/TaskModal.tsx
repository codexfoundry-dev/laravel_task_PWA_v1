import React from 'react'

type Props = { open: boolean; onClose: ()=>void }

export function TaskModal({ open, onClose }: Props) {
  if (!open) return null
  return (
    <div className="fixed inset-0 bg-black/40 backdrop-blur-xl flex items-center justify-center">
      <div className="glass-card w-full max-w-2xl p-6">
        <div className="text-lg font-semibold mb-4">Edit Task</div>
        <div className="grid gap-3">
          <input className="glass-chip px-3 py-2" placeholder="Title" />
          <textarea className="glass-chip px-3 py-2" placeholder="Description" />
        </div>
        <div className="mt-6 flex justify-end gap-2">
          <button className="glass-chip px-3 py-2" onClick={onClose}>Close</button>
          <button className="glass-chip px-3 py-2">Save</button>
        </div>
      </div>
    </div>
  )
}