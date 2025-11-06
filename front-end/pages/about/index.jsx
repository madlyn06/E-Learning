import Brands from '@/components/common/Brands'
import Instractors from '@/components/homes/home-1/Instractors'
import Testimonials from '@/components/homes/home-1/Testimonials'
import Layout from '@/components/layouts'
import About from '@/components/otherPages/about/About'
import Banner from '@/components/otherPages/about/Banner'
import Facts from '@/components/otherPages/about/Facts'
import Features from '@/components/otherPages/about/Features'
import OurVisions from '@/components/otherPages/about/OurVisions'
import { serverSideTranslations } from 'next-i18next/serverSideTranslations'
import Link from 'next/link'
import React from 'react'

export default function Page() {
  return (
    <>
      <Layout title={'About'}>
        <div className='page-title basic'>
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
                    <li>Instructor</li>
                  </ul>
                  <h2 className='font-cardo fw-7'>About Us</h2>
                  <h6>Become an instructor and change lives — including your own</h6>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className='main-content pt-0'>
          <About />
          <Features />
          <OurVisions />
          <Facts />
          <Testimonials parentClass='tf-spacing-5 widget-saying bg-4 page-about' />
          <div className='tf-spacing-5 pt-0'></div>
          <Instractors />
          <Brands />
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
