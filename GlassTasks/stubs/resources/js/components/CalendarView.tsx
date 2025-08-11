import React, { useRef } from 'react'
import FullCalendar from '@fullcalendar/react'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'

export function CalendarView() {
  const ref = useRef<FullCalendar | null>(null)

  return (
    <div className="calendar glass-subcard p-2">
      <FullCalendar
        ref={ref as any}
        plugins={[dayGridPlugin, timeGridPlugin, interactionPlugin]}
        initialView="dayGridMonth"
        editable
        selectable
        height="auto"
        events={async (info, success) => {
          const res = await fetch(`/api/v1/calendar?start=${info.startStr}&end=${info.endStr}`, { credentials: 'include' })
          const data = await res.json()
          success(data)
        }}
        eventDrop={async (info) => {
          await fetch(`/api/v1/tasks/${info.event.id}`, {
            method: 'PUT', headers: { 'Content-Type': 'application/json' }, credentials: 'include',
            body: JSON.stringify({ due_at: info.event.start?.toISOString() })
          })
        }}
      />
    </div>
  )
}