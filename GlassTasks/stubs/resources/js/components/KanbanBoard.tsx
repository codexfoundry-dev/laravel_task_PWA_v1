import React from 'react'
import { motion } from 'framer-motion'

const columns = [
  { key: 'todo', title: 'To Do' },
  { key: 'doing', title: 'Doing' },
  { key: 'done', title: 'Done' },
] as const

export function KanbanBoard() {
  return (
    <div className="grid grid-cols-3 gap-4">
      {columns.map((col) => (
        <div key={col.key} className="glass-subcard p-3 min-h-[60vh]">
          <div className="font-semibold mb-2">{col.title}</div>
          <div className="space-y-2">
            {[1,2,3].map((i) => (
              <motion.div key={i} whileHover={{ scale: 1.01 }} className="glass-chip p-3">Task {i}</motion.div>
            ))}
          </div>
        </div>
      ))}
    </div>
  )
}