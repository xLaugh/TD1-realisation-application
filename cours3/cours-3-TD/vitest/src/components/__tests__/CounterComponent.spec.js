import { describe, it, expect, beforeEach } from 'vitest'
import { mount } from '@vue/test-utils'
import { createPinia, setActivePinia } from 'pinia'
import CounterComponent from '../CounterComponent.vue'
import { useCounterStore } from '@/stores/counter'

describe('CounterComponent', () => {
    beforeEach(() => {
        setActivePinia(createPinia())
    })

    it('affiche la valeur initiale du compteur', () => {
        const wrapper = mount(CounterComponent)
        const counterValue = wrapper.find('#counter')
        expect(counterValue.text()).toBe('0')
    })

    it('appelle increment() au clic sur le bouton Increment', async () => {
        const wrapper = mount(CounterComponent)
        const counterStore = useCounterStore()

        // On vérifie que le store est bien utilisé
        const incrementBtn = wrapper.findAll('button').find(b => b.text() === 'Increment')
        await incrementBtn.trigger('click')

        expect(counterStore.count).toBe(1)
        expect(wrapper.find('#counter').text()).toBe('1')
    })

    it('appelle decrement() au clic sur le bouton Decrement', async () => {
        const wrapper = mount(CounterComponent)
        const counterStore = useCounterStore()

        const decrementBtn = wrapper.findAll('button').find(b => b.text() === 'Decrement')
        await decrementBtn.trigger('click')

        expect(counterStore.count).toBe(-1)
        expect(wrapper.find('#counter').text()).toBe('-1')
    })
})
