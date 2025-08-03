import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export type Theme = 'light' | 'dark' | 'space' | 'fire'

export interface ThemeConfig {
  name: string
  description: string
  colors: {
    primary: string
    secondary: string
    accent: string
    background: string
    surface: string
    text: string
    textSecondary: string
    border: string
    success: string
    warning: string
    error: string
  }
  gradients: {
    primary: string
    secondary: string
    background: string
  }
}

const themes: Record<Theme, ThemeConfig> = {
  light: {
    name: 'Светлая',
    description: 'Классическая светлая тема',
    colors: {
      primary: '#3B82F6',
      secondary: '#8B5CF6',
      accent: '#10B981',
      background: '#FFFFFF',
      surface: '#F8FAFC',
      text: '#1E293B',
      textSecondary: '#64748B',
      border: '#E2E8F0',
      success: '#10B981',
      warning: '#F59E0B',
      error: '#EF4444'
    },
    gradients: {
      primary: 'linear-gradient(135deg, #3B82F6 0%, #8B5CF6 100%)',
      secondary: 'linear-gradient(135deg, #10B981 0%, #059669 100%)',
      background: 'linear-gradient(135deg, #F8FAFC 0%, #E2E8F0 100%)'
    }
  },
  dark: {
    name: 'Темная',
    description: 'Современная темная тема',
    colors: {
      primary: '#60A5FA',
      secondary: '#A78BFA',
      accent: '#34D399',
      background: '#0F172A',
      surface: '#1E293B',
      text: '#F8FAFC',
      textSecondary: '#CBD5E1',
      border: '#334155',
      success: '#34D399',
      warning: '#FBBF24',
      error: '#F87171'
    },
    gradients: {
      primary: 'linear-gradient(135deg, #60A5FA 0%, #A78BFA 100%)',
      secondary: 'linear-gradient(135deg, #34D399 0%, #059669 100%)',
      background: 'linear-gradient(135deg, #0F172A 0%, #1E293B 100%)'
    }
  },
  space: {
    name: 'Космос',
    description: 'Успокаивающая космическая тема',
    colors: {
      primary: '#8B5CF6',
      secondary: '#EC4899',
      accent: '#10B981',
      background: '#0A0A0F',
      surface: '#1A1A2E',
      text: '#FFFFFF',
      textSecondary: '#B8C5D6',
      border: '#2D3748',
      success: '#10B981',
      warning: '#F59E0B',
      error: '#EF4444'
    },
    gradients: {
      primary: 'linear-gradient(135deg, #8B5CF6 0%, #EC4899 100%)',
      secondary: 'linear-gradient(135deg, #10B981 0%, #059669 100%)',
      background: 'linear-gradient(135deg, #0A0A0F 0%, #1A1A2E 100%)'
    }
  },
  fire: {
    name: 'Огонь',
    description: 'Теплая огненная тема',
    colors: {
      primary: '#F97316',
      secondary: '#EF4444',
      accent: '#22C55E',
      background: '#1A0F0F',
      surface: '#2D1B1B',
      text: '#FFFFFF',
      textSecondary: '#F3E8E8',
      border: '#4A2C2C',
      success: '#22C55E',
      warning: '#EAB308',
      error: '#DC2626'
    },
    gradients: {
      primary: 'linear-gradient(135deg, #F97316 0%, #EF4444 100%)',
      secondary: 'linear-gradient(135deg, #22C55E 0%, #16A34A 100%)',
      background: 'linear-gradient(135deg, #1A0F0F 0%, #2D1B1B 100%)'
    }
  }
}

export const useThemeStore = defineStore('theme', () => {
  const currentTheme = ref<Theme>('light')
  const followSystemTheme = ref(false)
  const systemTheme = ref<'light' | 'dark'>('light')

  const currentThemeConfig = computed(() => themes[currentTheme.value])

  const initTheme = () => {
    // Загружаем сохраненную тему
    const savedTheme = localStorage.getItem('theme') as Theme
    const savedFollowSystem = localStorage.getItem('followSystemTheme') === 'true'
    
    if (savedTheme && themes[savedTheme]) {
      currentTheme.value = savedTheme
    }
    
    followSystemTheme.value = savedFollowSystem
    
    if (followSystemTheme.value) {
      systemTheme.value = getSystemTheme()
      currentTheme.value = systemTheme.value === 'dark' ? 'dark' : 'light'
    }
    
    applyTheme()
  }

  const setTheme = (theme: Theme) => {
    if (themes[theme]) {
      currentTheme.value = theme
      followSystemTheme.value = false
      localStorage.setItem('theme', theme)
      localStorage.setItem('followSystemTheme', 'false')
      applyTheme()
    }
  }

  const setFollowSystemTheme = (follow: boolean) => {
    followSystemTheme.value = follow
    localStorage.setItem('followSystemTheme', follow.toString())
    
    if (follow) {
      systemTheme.value = getSystemTheme()
      currentTheme.value = systemTheme.value === 'dark' ? 'dark' : 'light'
      applyTheme()
    }
  }

  const applyTheme = () => {
    const theme = themes[currentTheme.value]
    const root = document.documentElement

    console.log('Applying theme:', currentTheme.value, theme)

    // Применяем CSS переменные
    Object.entries(theme.colors).forEach(([key, value]) => {
      root.style.setProperty(`--color-${key}`, value)
      console.log(`Setting --color-${key}:`, value)
    })

    Object.entries(theme.gradients).forEach(([key, value]) => {
      root.style.setProperty(`--gradient-${key}`, value)
      console.log(`Setting --gradient-${key}:`, value)
    })

    // Удаляем все существующие классы тем
    root.classList.remove('theme-light', 'theme-dark', 'theme-space', 'theme-fire')
    
    // Добавляем класс темы
    root.classList.add(`theme-${currentTheme.value}`)
    root.setAttribute('data-theme', currentTheme.value)
    
    // Добавляем класс dark для Tailwind, если это темная тема
    if (currentTheme.value === 'dark' || currentTheme.value === 'space' || currentTheme.value === 'fire') {
      root.classList.add('dark')
    } else {
      root.classList.remove('dark')
    }

    console.log('Theme applied, root classes:', root.className)
  }

  const getCurrentTheme = () => currentThemeConfig.value

  const getSystemTheme = (): 'light' | 'dark' => {
    if (typeof window !== 'undefined' && window.matchMedia) {
      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
      return mediaQuery.matches ? 'dark' : 'light'
    }
    return 'light'
  }

  const watchSystemTheme = (callback: (theme: 'light' | 'dark') => void) => {
    if (typeof window !== 'undefined' && window.matchMedia) {
      const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
      
      const handleChange = (e: MediaQueryListEvent) => {
        callback(e.matches ? 'dark' : 'light')
      }
      
      mediaQuery.addEventListener('change', handleChange)
      
      return () => {
        mediaQuery.removeEventListener('change', handleChange)
      }
    }
    return () => {}
  }

  return {
    currentTheme,
    followSystemTheme,
    systemTheme,
    currentThemeConfig,
    themes,
    initTheme,
    setTheme,
    setFollowSystemTheme,
    applyTheme,
    getCurrentTheme,
    getSystemTheme,
    watchSystemTheme
  }
}) 