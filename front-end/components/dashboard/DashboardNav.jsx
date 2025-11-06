'use client'
import Link from 'next/link'
import { useRouter } from 'next/router'
import React, { useEffect } from 'react'
import { Button } from '../ui/Button'
import { useLoading } from '@/hooks/useLoading'
import apiFetch from '@/utils/request'
import { toast } from 'react-toastify'
import { useAuth } from '@/context/AuthContext'

const dashboardItems = [
  {
    href: '/dashboard',
    iconClass: 'flaticon-activity',
    label: 'Dashboard',
    active: true
  },
  {
    href: '/dashboard/instructor-courses',
    iconClass: 'flaticon-document',
    label: 'Khoá học đã tạo'
  },
  {
    href: '/dashboard/my-courses',
    iconClass: 'flaticon-play-1',
    label: 'Khoá học đã đăng ký'
  },
  {
    href: '/dashboard/reviews',
    iconClass: 'flaticon-message-1',
    label: 'Đánh giá'
  },
  {
    href: '/dashboard/wishlist',
    iconClass: 'flaticon-heart',
    label: 'Yêu thích'
  },
  {
    href: '/dashboard/quizzes',
    iconClass: 'flaticon-question',
    label: 'Bài kiểm tra'
  },
  {
    href: '/dashboard/orders',
    iconClass: 'flaticon-bag',
    label: 'Đơn hàng'
  },
  {
    href: '/dashboard/setting',
    iconClass: 'flaticon-setting-1',
    label: 'Cài đặt'
  },
  { href: '/', iconClass: 'flaticon-export', label: 'Đăng xuất' }
]
export default function DashboardNav() {
  const { pathname } = useRouter()

  const { loading, withLoading } = useLoading()
  const { logout } = useAuth()

  const router = useRouter()

  useEffect(() => {
    const toggleElement = document.querySelector('.dashboard_navigationbar .dropbtn')
    const dashboardNav = document.querySelector('.dashboard_navigationbar .instructors-dashboard')
    const handleOutsideClick = (event) => {
      if (toggleElement && dashboardNav) {
        // Check if the click is outside both toggleElement and dashboardNav
        if (!toggleElement.contains(event.target) && !dashboardNav.contains(event.target)) {
          // Add your logic here (e.g., hide the dropdown or remove a class)
          dashboardNav.classList.remove('show')
          toggleElement.classList.remove('show')
        }
      }
    }
    const toggleOpen = () => {
      toggleElement.classList.toggle('show')
      dashboardNav.classList.toggle('show')
    }
    toggleElement.addEventListener('click', toggleOpen)
    document.addEventListener('click', handleOutsideClick)
    return () => {
      toggleElement.removeEventListener('click', toggleOpen)
      document.removeEventListener('click', handleOutsideClick)
    }
  }, [])

  const handleLogout = async () => {
    await withLoading(async (data) => {
      try {
        await apiFetch.post('v1/elearning/users/logout')
        router.push('/auth/login')
        toast.success('Logout successfully')
        logout()
      } catch (error) {
        toast.error('Logout failed')
        // router.push('/auth/login')
      }
    })
  }

  return (
    <>
      <>
        {dashboardItems.map((item, index) => {
          if (item.href === '/')
            return (
              <Button
                key={index}
                onClick={handleLogout}
                disabled={loading}
                className={`dashboard-item`}
                href={item.href}
              >
                <i className={item.iconClass} />
                {item.label}
              </Button>
            )
          return (
            <Link key={index} className={`dashboard-item ${pathname == item.href ? 'active' : ''}`} href={item.href}>
              <i className={item.iconClass} />
              {item.label}
            </Link>
          )
        })}
      </>
    </>
  )
}
