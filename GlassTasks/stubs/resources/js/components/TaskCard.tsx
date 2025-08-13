import React from 'react'
import { motion } from 'framer-motion'

type Props = { title: string; priority?: 'low'|'med'|'high' }

export function TaskCard({ title, priority = 'med' }: Props) {
  const color = priority === 'high' ? 'border-red-400' : priority === 'low' ? 'border-emerald-400' : 'border-sky-400'
  return (
    <motion.div whileHover={{ scale: 1.02 }} className={`glass-chip p-3 border ${color}`}>
      <div className="text-sm">{title}</div>
    </motion.div>
  )
}