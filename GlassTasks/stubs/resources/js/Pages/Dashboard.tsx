import React, { useState } from 'react'
import { KanbanBoard } from '../components/KanbanBoard'
import { CalendarView } from '../components/CalendarView'
import { ProjectList } from '../components/ProjectList'
import { TodayPanel } from '../components/TodayPanel'
import { QuickAdd } from '../components/QuickAdd'
import { BellDropdown } from '../components/BellDropdown'
import { Shortcuts } from '../components/Shortcuts'

export default function Dashboard() {
  const [tab, setTab] = useState<'kanban' | 'calendar' | 'my'>('kanban')

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white">
      <Shortcuts onNew={() => { /* open modal */ }} onFocusSearch={() => { /* focus search */ }} onGoKanban={() => setTab('kanban')} onGoCalendar={() => setTab('calendar')} />
      <header className="sticky top-0 z-20 backdrop-blur-xl bg-white/5 border-b border-white/10">
        <div className="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
          <div className="flex items-center gap-4">
            <span className="text-xl font-bold">GlassTasks</span>
            <nav className="flex gap-2">
              {['kanban','calendar','my'].map((t) => (
                <button key={t} onClick={() => setTab(t as any)} className={`px-3 py-1 rounded-full ${tab===t? 'bg-white/20' : 'hover:bg-white/10'}`}>{t==='kanban'?'Kanban':t==='calendar'?'Calendar':'My Tasks'}</button>
              ))}
            </nav>
          </div>
          <BellDropdown />
        </div>
      </header>
      <main className="max-w-7xl mx-auto grid grid-cols-12 gap-4 p-4">
        <aside className="col-span-3 space-y-4">
          <div className="glass-card p-4">
            <ProjectList />
          </div>
          <div className="glass-card p-4">
            <QuickAdd />
          </div>
        </aside>
        <section className="col-span-6">
          <div className="glass-card p-4 min-h-[70vh]">
            {tab==='kanban' && <KanbanBoard />}
            {tab==='calendar' && <CalendarView />}
            {tab==='my' && <div>My assigned tasks coming soon</div>}
          </div>
        </section>
        <aside className="col-span-3">
          <div className="glass-card p-4 sticky top-20">
            <TodayPanel />
          </div>
        </aside>
      </main>
    </div>
  )
}