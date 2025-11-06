import React from 'react'
import Nav from './Nav'
import Link from 'next/link'
import Image from 'next/image'
import Categories from './Categories'
import MobileNav from './MobileNav'
import { useAuth } from '@/context/AuthContext'
import apiFetch from '@/utils/request'
import { useRouter } from 'next/router'

export default function Header() {
  const { profile, logout } = useAuth()
  const router = useRouter()

  const handleLogout = async () => {
    await apiFetch.post('/v1/elearning/users/logout')
    logout()
    router.push('/')
  }
  return (
    <header id='header_main' className='header'>
      <div className='header-inner'>
        <div className='header-inner-wrap'>
          <div className='header-left flex-grow'>
            <a
              className='mobile-nav-toggler mobile-button d-lg-none flex'
              type='button'
              data-bs-toggle='offcanvas'
              data-bs-target='#offcanvasMenu'
              aria-controls='offcanvasMenu'
            />
            <div id='site-logo'>
              <Link href={`/`} rel='home'>
                <Image id='logo-header' alt='' src='/images/logo/logo.svg' width={123} height={36} />
              </Link>
            </div>
            <div className='header-catalog'>
              <a href='#' className='header-text'>
                Categories
                <i className='icon-arrow-bottom' />
              </a>
              <Categories />
            </div>
            <div className='header-search flex-grow'>
              <form onSubmit={(e) => e.preventDefault()} className='form-search'>
                <fieldset>
                  <input
                    className=''
                    type='text'
                    placeholder='Search for anything'
                    name='text'
                    tabIndex={2}
                    defaultValue=''
                    aria-required='true'
                    required
                  />
                </fieldset>
                <div className='button-submit'>
                  <button className='' type='submit'>
                    <i className='icon-search fs-20' />
                  </button>
                </div>
              </form>
            </div>
          </div>
          <div className='header-right'>
            <nav className='main-menu'>
              <ul className='navigation'>
                <Nav />
              </ul>
            </nav>
            <a className='d-lg-none flex' href='#canvasSearch' data-bs-toggle='offcanvas' aria-controls='offcanvasLeft'>
              <i className='icon-search fs-20' />
            </a>
            <Link href={`/shop-cart`} className='header-cart flex items-center justify-center'>
              <i className='icon-shopcart fs-18' />
            </Link>
            {!profile ? (
              <div className='header-btn flex gap-10'>
                <div className='header-login'>
                  <Link href={`/auth/login`} className='tf-button-default header-text'>
                    {'Đăng nhập'}
                  </Link>
                </div>
                <div className='header-register'>
                  <Link href={`/auth/register`} className='tf-button-default active header-text'>
                    {'Đăng ký'}
                  </Link>
                </div>
              </div>
            ) : (
              <div className='d-flex align-items-center'>
                <nav className='main-menu'>
                  <ul className='navigation'>
                    <li className='has-children'>
                      <a href='#' className=''>
                        {profile?.name}
                      </a>
                      <ul
                        className=''
                        style={{
                          marginLeft: '-50px',
                          minWidth: '230px'
                        }}
                      >
                        <li className=''>
                          <Link href={`/dashboard`} className=''>
                            Dashboard
                          </Link>
                        </li>
                        <li className=''>
                          <Link href={`/become-teacher`} className=''>
                            Become a teacher
                          </Link>
                        </li>
                        <li className=''>
                          <Link href='#' onClick={() => handleLogout()}>
                            Logout
                          </Link>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </nav>
              </div>
            )}
          </div>
        </div>
      </div>
      <MobileNav />
    </header>
  )
}
