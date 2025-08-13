import React from 'react'

export function TodayPanel() {
  return (
    <div>
      <div className="font-semibold mb-2">Today</div>
      <div className="space-y-2">
        <div className="glass-chip p-2">Overdue: Sample task</div>
        <div className="glass-chip p-2">Due Today: Another task</div>
      </div>
    </div>
  )
}