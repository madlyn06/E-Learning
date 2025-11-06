import React from 'react'
import Head from 'next/head'
import Footer from '../footers/Footer'
import Header from '../headers/Header'
import PageTitle from '../dashboard/PageTitle'
import DashboardNav from '../dashboard/DashboardNav'

function Layout({ title = 'Default Title', children, showPageTitle = false, isDashboard = false }) {
  const fullTitle = `${title} || Elearning System`
  return (
    <>
      <Head>
        <title>{fullTitle}</title>
        <meta name='description' content='Elearning System' />
      </Head>
      <div id='wrapper'>
        <div className='tf-top-bar flex items-center justify-center'>
          <p>Intro price. Get UpSkill for Big Sale -95% off.</p>
        </div>
        <Header />
        {showPageTitle && <PageTitle />}
        {isDashboard ? (
          <div className='main-content pt-0'>
            <div className='page-inner tf-spacing-1'>
              <div className='tf-container'>
                <div className='row'>
                  <div className='col-xl-3 col-lg-12'>
                    <div className='dashboard_navigationbar'>
                      <div className='dropbtn'>
                        <i className='icon-home' /> Dashboard Navigation
                      </div>
                      <div className='instructors-dashboard'>
                        <div className='dashboard-title'>
                          INSTRUCTOR DASHBOARD
                          <DashboardNav />
                        </div>
                      </div>
                    </div>
                  </div>
                  {children}
                </div>
              </div>
            </div>
          </div>
        ) : (
          children
        )}
        <Footer parentClass='footer has-border-top' />
      </div>
    </>
  )
}

export default Layout
