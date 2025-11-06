import React from 'react'
import Pagination from '../common/Pagination'
import Image from 'next/image'
import Link from 'next/link'
import { formatPrice } from '@/utils/common'
import { useTranslation } from 'next-i18next'

export default function Dashboard({ courses, summaryCourses }) {
  const { t } = useTranslation('instructor')
  return (
    <div className='col-xl-9 col-lg-12'>
      <div className='section-dashboard-right'>
        <div className='section-icons'>
          <div className='row'>
            <div className='icons-items'>
              <div className='icons-box style-4 wow fadeInUp'>
                <div className='icons'>
                  <i className='flaticon-play-2' />
                </div>
                <div className='content'>
                  <h6>Total Course</h6>
                  <span className='num-count fs-26 fw-5'>{summaryCourses.total_course}</span>
                </div>
              </div>
              <div className='icons-box style-4 wow fadeInUp' data-wow-delay='0.1s'>
                <div className='icons'>
                  <i className='flaticon-alarm' />
                </div>
                <div className='content'>
                  <h6>Published Course</h6>
                  <span className='num-count fs-26 fw-5'>{summaryCourses.published_course}</span>
                </div>
              </div>
              <div className='icons-box style-4 wow fadeInUp' data-wow-delay='0.2s'>
                <div className='icons'>
                  <i className='flaticon-video' />
                </div>
                <div className='content'>
                  <h6>Pending Course</h6>
                  <span className='num-count fs-26 fw-5'>{summaryCourses.pending_course}</span>
                </div>
              </div>
            </div>
            <div className='icons-items'>
              <div className='icons-box style-4 wow fadeInUp'>
                <div className='icons'>
                  <i className='flaticon-user' />
                </div>
                <div className='content'>
                  <h6>Total Student</h6>
                  <span className='num-count fs-26 fw-5'>{summaryCourses.total_students}</span>
                </div>
              </div>
              <div className='icons-box style-4 wow fadeInUp' data-wow-delay='0.1s'>
                <div className='icons'>
                  <i className='flaticon-user-2' />
                </div>
                <div className='content'>
                  <h6>Student Completed</h6>
                  <span className='num-count fs-26 fw-5'>{summaryCourses.students_completed}</span>
                </div>
              </div>
              <div className='icons-box style-4 wow fadeInUp' data-wow-delay='0.2s'>
                <div className='icons'>
                  <i className='flaticon-graduation' />
                </div>
                <div className='content'>
                  <h6>Student In-progress</h6>
                  <span className='num-count fs-26 fw-5'>{summaryCourses.students_in_progress}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        {/* section-learn */}
        <div className='section-learn'>
          <div className='heading-section flex justify-between items-center'>
            <h6 className='fw-5 fs-22 wow fadeInUp'>Best Selling Courses</h6>
            <a href='#' className='tf-btn-arrow wow fadeInUp' data-wow-delay='0.1s'>
              View All <i className='icon-arrow-top-right' />
            </a>
          </div>
          <div className='wg-box'>
            <div className='table-selling-course wow fadeInUp'>
              <div className='head'>
                <div className='item'>
                  <div className='fs-15 fw-5'>{t('courseName')}</div>
                </div>
                <div className='item'>
                  <div className='fs-15 fw-5'>{t('sales')}</div>
                </div>
                <div className='item'>
                  <div className='fs-15 fw-5'>{t('salePrice')}</div>
                </div>
                <div className='item'>
                  <div className='fs-15 fw-5'>Action</div>
                </div>
              </div>
              <ul>
                {courses.data.map((course) => (
                  <li key={course.id}>
                    <div className='selling-course-item item my-20 ptable-20 border-bottom'>
                      <div className='image'>
                        <Image
                          className='lazyload'
                          src='/images/courses/courses-01.jpg'
                          data-=''
                          alt=''
                          width={520}
                          height={380}
                        />
                      </div>
                      <div className='title'>
                        <Link className='fs-15 fw-5' href={`/dashboard/instructor-courses/${course.id}`}>
                          {course.name}
                        </Link>
                      </div>
                      <div>
                        <p className='fs-15 fw-5'>34</p>
                      </div>
                      <div>
                        <p className='fs-15 fw-5'>{formatPrice(course.sale_price)}</p>
                      </div>
                      <div>
                        <div className='selling-course-btn btn-style-2'>
                          <a href='#' className='btn-edit btn'>
                            <i className='flaticon-edit' />
                          </a>
                          <a href='#' className='btn-remove btn'>
                            <i className='flaticon-close' />
                          </a>
                        </div>
                      </div>
                    </div>
                  </li>
                ))}
              </ul>
            </div>
          </div>
          <ul className='wg-pagination justify-center pt-0'>
            <Pagination />
          </ul>
        </div>
      </div>
      {/* section-learn */}
    </div>
  )
}
