import { describe, it, expect } from 'vitest'
import { mount } from '@vue/test-utils'
import InputField from '../InputField.vue'

describe('InputField', () => {
    it('affiche le texte saisi en temps réel', async () => {
        const wrapper = mount(InputField)
        const input = wrapper.find('input')
        const display = wrapper.find('p span')

        // Initialement vide
        expect(display.text()).toBe('')

        // Simulation d'une saisie
        await input.setValue('Hello Vitest')

        expect(display.text()).toBe('Hello Vitest')
    })

    it('possède le label correct', () => {
        const wrapper = mount(InputField)
        expect(wrapper.find('label').text()).toBe('Text:')
    })
})
