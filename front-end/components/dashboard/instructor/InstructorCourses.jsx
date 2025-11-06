import React from 'react'
import CourseForm from './CourseForm'
import { format } from 'date-fns'
import Pagination from '@/components/common/Pagination'
import { useCourseInstructor } from '@/hooks/useCourseInstructor'
import Link from 'next/link'
import Processing from '@/components/ui/Processing'
import DeleteCourseModal from './DeleteCourseModal'
import { useTranslation } from 'next-i18next'
import { formatPrice } from '@/utils/common'

export default function InstructorCourses() {
  const { t } = useTranslation('instructor')
  const [{ data: courses, mutate }, { setPage, deleteCourse, createCourse }] = useCourseInstructor()

  if (!courses) return <Processing visible />

  const coursesData = courses.data;

  return (
    <div className='col-xl-9 col-lg-12'>
      <div className='section-order-right section-right'>
        <div className='heading-section pb-13 border-bottom flex justify-between items-center'>
          <h6 className='fs-22 fw-5 wow fadeInUp'>{t('instructorCourses')}</h6>
          <CourseForm handleCourse={createCourse} />
        </div>
        <div className='wg-box'>
          <div className='table-order wow fadeInUp'>
            <div className='head'>
              <div className='item'>
                <div className='fs-15 fw-5'>{t('ID')}</div>
              </div>
              <div className='item'>
                <div className='fs-15 fw-5'>{t('courseName')}</div>
              </div>
              <div className='item'>
                <div className='fs-15 fw-5'>{t('salePrice')}</div>
              </div>
              <div className='item'>
                <div className='fs-15 fw-5'>{t('date')}</div>
              </div>
              <div className='item'>
                <div className='fs-15 fw-5'>{t('status')}</div>
              </div>
              <div className='item'>
                <div className='fs-15 fw-5'>{t('Actions')}</div>
              </div>
            </div>
            <ul>
              {coursesData.data &&
                coursesData.data.length > 0 &&
                coursesData.data.map((course) => (
                  <li key={course.id}>
                    <div className='order-item item border-bottom items-center'>
                      <div className='item'>
                        <p className='fs-15 fw-5'>{course.id}</p>
                      </div>
                      <div>
                        <Link href={`instructor-courses/${course.id}`} className='fs-15 fw-5'>
                          {course.name}
                        </Link>
                      </div>
                      <div>
                        <p className='fs-15 fw-5'>{formatPrice(course.sale_price)}</p>
                      </div>
                      <div>
                        <p className='fs-15 fw-5'>{format(new Date(course.created_at), 'dd/MM/yyyy')}</p>
                      </div>
                      <div>
                        {
                          course.is_enable ? (
                            <p className='fs-15 fw-5 text-success'>{t('active')}</p>
                          ) : (
                            <p className='fs-15 fw-5 text-danger'>{t('draft')}</p>
                          )
                        }
                      </div>
                      <div>
                        <div className=' btn-style-2 fs-15'>
                          <Link href={`instructor-courses/${course.id}`} className='btn-edit btn'>
                            <i className='flaticon-eye' />
                          </Link>
                          <DeleteCourseModal id={course.id} mutate={mutate} deleteCourse={deleteCourse} />
                        </div>
                      </div>
                    </div>
                  </li>
                ))}
            </ul>
            <ul className='wg-pagination justify-center my-4'>
              <Pagination
                currentPage={courses.data.pagination.current_page}
                total={courses.data.pagination.total}
                perPage={courses.data.pagination.per_page}
                setPage={setPage}
              />
            </ul>
          </div>
        </div>
      </div>
    </div>
  )
}
