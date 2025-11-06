import { getToken, removeToken, setToken } from '@/utils/auth'
import { LS_KEYS } from '@/utils/const'
import { useRouter } from 'next/navigation'
import { createContext, useState, useContext, useEffect } from 'react'

const AuthContext = createContext()

export const AuthProvider = ({ children }) => {
  const [profile, setProfile] = useState(null)
  const router = useRouter()

  useEffect(() => {
    const savedProfile = localStorage.getItem(LS_KEYS.PROFILE)
    console.log('first')
    if (savedProfile) {
      setProfile(JSON.parse(savedProfile))
    }
  }, [])

  useEffect(() => {
    const token = getToken()
    if (!token) {
      logout()
    }
  }, [router])

  const login = (userData, token) => {
    setProfile(userData)
    localStorage.setItem(LS_KEYS.PROFILE, JSON.stringify(userData))
    setToken(token)
  }

  const logout = () => {
    setProfile(null)
    localStorage.removeItem(LS_KEYS.PROFILE)
    removeToken()
  }

  return <AuthContext.Provider value={{ profile, login, logout, setProfile }}>{children}</AuthContext.Provider>
}

export const useAuth = () => useContext(AuthContext)
