import Testimonials from '@/components/homes/home-1/Testimonials'
import Banner from '@/components/otherPages/become-teacher/Banner'
import Facts from '@/components/otherPages/become-teacher/Facts'
import Features from '@/components/otherPages/become-teacher/Features'
import Process from '@/components/otherPages/become-teacher/Process'
import React from 'react'
import Link from 'next/link'
import Layout from '@/components/layouts'
import { serverSideTranslations } from 'next-i18next/serverSideTranslations'

export default function Page() {
  return (
    <>
      <Layout title='Become Teacher'>
        <div className='page-title basic page-become-teacher'>
          <div className='tf-container full'>
            <div className='row'>
              <div className='col-12'>
                <div className='content text-center'>
                  <ul className='breadcrumbs flex items-center justify-center gap-10'>
                    <li>
                      <Link href={`/`} className='flex'>
                        <i className='icon-home' />
                      </Link>
                    </li>
                    <li>
                      <i className='icon-arrow-right' />
                    </li>
                    <li>Pages</li>
                    <li>
                      <i className='icon-arrow-right' />
                    </li>
                    <li>Insructor</li>
                  </ul>
                  <h2 className='font-cardo fw-7'>Become A Teacher</h2>
                  <h6>Become an instructor and change lives — including your own</h6>
                  <div className='d-flex justify-center'>
                    <a href='#' className='tf-btn'>
                      Get Started
                      <i className='icon-arrow-top-right' />
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className='main-content pt-0'>
          <Features />
          <Process />
          <Facts />
          <Testimonials parentClass='tf-spacing-5 pt-0 widget-saying bg-4 bg-white' />
          <Banner />
        </div>
      </Layout>
    </>
  )
}

export async function getStaticProps({ locale }) {
  return {
    props: {
      ...(await serverSideTranslations(locale, ['common']))
    }
  }
}
