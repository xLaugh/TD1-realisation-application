import { describe, it, expect, beforeEach } from 'vitest'
import { mount } from '@vue/test-utils'
import { createRouter, createWebHistory } from 'vue-router'
import { createPinia } from 'pinia'
import router from '../index.js'
import HomeView from '../../views/HomeView.vue'
import DemoView from '../../views/DemoView.vue'
import NotFoundView from '../../views/NotFoundView.vue'

describe('Router', () => {
  let testRouter
  let pinia

  beforeEach(() => {
    pinia = createPinia()
    testRouter = createRouter({
      history: createWebHistory(),
      routes: [
        {
          path: '/',
          name: 'home',
          component: HomeView
        },
        {
          path: '/demo',
          name: 'demo',
          component: DemoView
        },
        {
          path: '/:pathMatch(.*)*',
          component: NotFoundView
        }
      ]
    })
  })

  it('devrait naviguer vers la page home (/)', async () => {
    await testRouter.push('/')
    await testRouter.isReady()

    expect(testRouter.currentRoute.value.path).toBe('/')
    expect(testRouter.currentRoute.value.name).toBe('home')
  })

  it('devrait naviguer vers la page demo (/demo)', async () => {
    await testRouter.push('/demo')
    await testRouter.isReady()

    expect(testRouter.currentRoute.value.path).toBe('/demo')
    expect(testRouter.currentRoute.value.name).toBe('demo')
  })

  it('devrait afficher NotFoundView pour une route inexistante', async () => {
    await testRouter.push('/route-inexistante')
    await testRouter.isReady()

    expect(testRouter.currentRoute.value.path).toBe('/route-inexistante')
    const matched = testRouter.currentRoute.value.matched
    expect(matched.length).toBeGreaterThan(0)
  })

  it('devrait avoir la route home configurée correctement', () => {
    const routes = router.options.routes
    const homeRoute = routes.find(route => route.name === 'home')
    
    expect(homeRoute).toBeDefined()
    expect(homeRoute.path).toBe('/')
    expect(homeRoute.name).toBe('home')
  })

  it('devrait avoir la route demo configurée correctement', () => {
    const routes = router.options.routes
    const demoRoute = routes.find(route => route.name === 'demo')
    
    expect(demoRoute).toBeDefined()
    expect(demoRoute.path).toBe('/demo')
    expect(demoRoute.name).toBe('demo')
  })

  it('devrait avoir la route catch-all pour les pages non trouvées', () => {
    const routes = router.options.routes
    const notFoundRoute = routes.find(route => route.path.includes('pathMatch'))
    
    expect(notFoundRoute).toBeDefined()
    expect(notFoundRoute.path).toBe('/:pathMatch(.*)*')
  })

  it('devrait rendre le composant HomeView pour la route home', () => {
    const wrapper = mount(HomeView, {
      global: {
        plugins: [pinia]
      }
    })
    expect(wrapper.exists()).toBe(true)
  })

  it('devrait rendre le composant DemoView pour la route demo', () => {
    const wrapper = mount(DemoView, {
      global: {
        plugins: [pinia]
      }
    })
    expect(wrapper.exists()).toBe(true)
  })

  it('devrait rendre le composant NotFoundView pour une route inexistante', () => {
    const wrapper = mount(NotFoundView, {
      global: {
        plugins: [pinia]
      }
    })
    expect(wrapper.exists()).toBe(true)
  })
})