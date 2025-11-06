'use client'

import Link from 'next/link'
import React from 'react'
import { useRouter } from 'next/router'
import { useAppData } from '@/context/AppDataContext';

export default function Nav() {
  const app = useAppData();
  const menuItems = app?.menu?.items || [];

  const { pathname } = useRouter()
  const isMenuActive = (menu) => {
    let isActive = false
    if (menu.url !== '#') {
      if (pathname.split('/')[1] == menu.url?.split('/')[1]) {
        isActive = true
      }
    }
    if (menu.children) {
      menu.children.forEach((el) => {
        if (el.url != '#') {
          if (pathname.split('/')[1] == el.url?.split('/')[1]) {
            isActive = true
          }
        }
        if (el.children) {
          el.children.map((elm) => {
            if (elm.url != '#') {
              if (pathname.split('/')[1] == elm.url?.split('/')[1]) {
                isActive = true
              }
            }
          })
        }
      })
    }
    return isActive
  }

  return (
    <>
      {menuItems.map((item, index) => (
        <li key={index} className={item.isActive ? 'has-children current' : item.children.length > 0 ? 'has-children' : ''}>
          <a
            href={item.url}
            className={isMenuActive(item) ? 'parent-active activeMenu' : ''}
          >
            {item.label}
          </a>
          {item.children.length > 0 && item.children && (
            <ul className={item.hasMega ? 'mega-menu' : ''}>
              {item.children.map((subItem, subIndex) => (
                <li key={subIndex} className={subItem.children.length > 0 ? 'has-item-children' : ''}>
                  {subItem.label && !subItem.url && (
                    <span className={`title ${isMenuActive(subItem) ? 'activeMenu' : ''}`}>{subItem.label}</span>
                  )}
                  {subItem.url ? (
                    <Link href={subItem.url} className={`${isMenuActive(subItem) ? 'activeMenu' : ''}`}>
                      {subItem.label}
                    </Link>
                  ) : (
                    subItem.children.length > 0 && (
                      <ul>
                        {subItem.children.map((subSubItem, subSubIndex) => (
                          <li key={subSubIndex}>
                            <Link href={subSubItem.url} className={`${isMenuActive(subSubItem) ? 'activeMenu' : ''}`}>
                              {subSubItem.label}
                            </Link>
                          </li>
                        ))}
                      </ul>
                    )
                  )}
                </li>
              ))}
            </ul>
          )}
        </li>
      ))}
    </>
  )
}
