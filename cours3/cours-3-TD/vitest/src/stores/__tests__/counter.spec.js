// stores/counter.spec.ts
import { setActivePinia, createPinia } from 'pinia'
import { describe, it, beforeEach, expect } from 'vitest'
import { useCounterStore } from '../counter.js'

describe('Counter Store', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
    })

    it('increments', () => {
        const counter = useCounterStore()
        counter.increment()
        expect(counter.count).toBe(1)
    })

    it('decrements', () => {
        const counter = useCounterStore()
        counter.decrement()
        expect(counter.count).toBe(-1)
    })
})
