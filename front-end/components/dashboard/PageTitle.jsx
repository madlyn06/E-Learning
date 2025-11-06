import React from 'react'
import Image from 'next/image'
import { useAuth } from '@/context/AuthContext'
import { useTranslation } from 'next-i18next'
import CourseForm from './instructor/CourseForm'
import { useCourseInstructor } from '@/hooks/useCourseInstructor'

export default function PageTitle() {
  const { profile } = useAuth()
  const { t } = useTranslation('common')
  const [, { createCourse }] = useCourseInstructor()

  const rating = Number(profile?.average_rating || 0)
  const filledStars = Math.max(0, Math.min(5, Math.round(rating)))
  const emptyStars = 5 - filledStars

  return (
    <div className='page-title style-9'>
      <div className='tf-container'>
        <div className='row items-center'>
          <div className='col-lg-8'>
            <div className='content'>
              <div className='author-item'>
                <div className='author-item-img'>
                  <Image alt='' src='/images/avatar/review-1.png' width={101} height={100} />
                </div>
              </div>
              <div className='title'>
                <h2 className='font-cardo fw-7'>
                  {t('welcome')}, {profile?.name}
                </h2>
                <ul className='entry-meta mt-4 mb-4'>
                  <li>
                    <div className='ratings'>
                      <div className='number'>{rating.toFixed(1)}</div>
                      {Array.from({ length: filledStars }).map((_, idx) => (
                        <i key={`star-full-${idx}`} className='icon-star-1' />
                      ))}
                      {Array.from({ length: emptyStars }).map((_, idx) => (
                        <svg
                          key={`star-empty-${idx}`}
                          width={12}
                          height={11}
                          viewBox='0 0 12 11'
                          fill='none'
                          xmlns='http://www.w3.org/2000/svg'
                        >
                          <path
                            d='M3.54831 7.10382L3.58894 6.85477L3.41273 6.67416L1.16841 4.37373L4.24914 3.90314L4.51288 3.86286L4.62625 3.62134L5.99989 0.694982L7.37398 3.62182L7.48735 3.86332L7.75108 3.9036L10.8318 4.37419L8.58749 6.67462L8.41128 6.85523L8.4519 7.10428L8.98079 10.3465L6.24201 8.8325L6.00014 8.69879L5.75826 8.83247L3.01941 10.3461L3.54831 7.10382ZM11.0444 4.15626L11.0442 4.15651L11.0444 4.15626Z'
                            stroke='#131836'
                          />
                        </svg>
                      ))}
                      <div className='total'>
                        {profile?.total_reviews} {t('rating')}
                      </div>
                    </div>
                  </li>
                  <li>
                    <i className='flaticon-user' />
                    {profile?.total_students} {t('enrolledStudents')}
                  </li>
                  <li>
                    <i className='flaticon-play-1'>
                      {profile?.total_courses} {t('courses')}
                    </i>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div className='col-lg-4'>
            <div className='right-content'>
              <CourseForm handleCourse={createCourse}></CourseForm>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
