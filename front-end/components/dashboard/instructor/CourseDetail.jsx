import { BookOpen, User, Play, FileText } from 'lucide-react'
import { Button } from '@/components/ui/Button'
import { SectionForm } from './SectionForm'
import { LessonForm } from './LessonForm'
import { useCourse } from '@/hooks/useCourse'
import { useParams } from 'next/navigation'
import LoadingLogo from '@/components/ui/Loading'
import Image from 'next/image'
import Accordions from '@/components/common/Accordions'
import DeleteSectionModal from './DeleteSectionModal'
import DeleteLessonModal from './DeleteLessonModal'
import CourseForm from './CourseForm'
import { useTranslation } from 'next-i18next'

export function CourseDetail() {
  const { slug } = useParams()
  const { t } = useTranslation('instructor')

  const [
    { data: course, error, isLoading },
    { updateCourse, addSection, updateSection, deleteSection, addLesson, deleteLesson, updateLesson }
  ] = useCourse(slug)

  const getLessonIcon = (type) => {
    switch (type) {
      case 'video':
        return <Play className='w-4 h-4' />
      case 'text':
        return <FileText className='w-4 h-4' />
      case 'quiz':
        return <BookOpen className='w-4 h-4' />
      default:
        return <FileText className='w-4 h-4' />
    }
  }

  if (!course) return <LoadingLogo />

  const totalLessons = course.sections.reduce((total, section) => total + section.lessons?.length, 0)

  return (
    <div className='section-my-courses-right section-right'>
      {/* Course Info */}
      <div className='course-detail__info-card'>
        <div className='content'>
          <div className='image'>
            <Image src={course.image || '/images/courses/courses-01.jpg'} alt={course?.name} width={100} height={100} />
          </div>

          <div className='details'>
            <div className='header'>
              <div className='info'>
                <h2 className='title'>{course.name}</h2>
                <p className='description'>{course?.description}</p>
              </div>
              <CourseForm course={course} handleCourse={updateCourse} />
            </div>

            {/* <div className='badges'>
              <span className='badge badge--secondary'>{getCategoryLabel(course?.category)}</span>
              {course?.level && (
                <span className={`badge badge--${getLevelColorClass(course?.level)}`}>
                  {getLevelLabel(course?.level)}
                </span>
              )}
            </div> */}
            <div className='stats'>
              <div className='stat'>
                <User />
                <span>{course.user.name}</span>
              </div>
              <div className='stat'>
                <BookOpen />
                <span>{totalLessons} {t('lessons')}</span>
              </div>
              <div className='stat'>
                <span>{t('numberStudents')}:</span>
                <span>50</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      {/* Course Content */}
      <div className='course-detail__tabs'>
        <div className='curriculum-header'>
          <h3 className='title'>{t('curriculum')}</h3>

          <SectionForm handleSection={addSection} courseId={slug} />
        </div>

        <div className='chapters'>
          <div className='chapter tf-accordion-style-3 tf-accordion'>
            {course.sections.map((section, index) => (
              <Accordions
                key={section.id}
                childrenHeader={
                  <div className='chapter__header'>
                    <div className='header-content'>
                      <div className='left'>
                        <div className='info' style={{ userSelect: 'none' }}>
                          <div className='title mx-3'>
                            {section.name}
                          </div>
                          <div className='description fs-4'>{section.description}</div>
                        </div>
                      </div>
                      <div className='right flex flex-column gap-2'>
                        <div className='flex gap-2'>
                          <SectionForm handleSection={updateSection} courseId={slug} section={section} />
                          <DeleteSectionModal id={section.id} deleteSection={deleteSection} />
                        </div>
                        <span className='fs-5 fw-semibold' style={{ userSelect: 'none' }}>
                          {section.lessons?.length} {t('lessons')}
                        </span>
                      </div>
                    </div>
                  </div>
                }
                childrenContent={
                  <div className='chapter__content'>
                    <div className='lessons'>
                      {section.lessons.map((lesson, lessonIndex) => (
                          <div className='lesson' key={lesson.id}>
                            <div className='left'>
                              <div
                                className={`lesson-number ${lesson.is_published ? 'completed' : 'incomplete'} fs-5 `}
                              >
                                {lessonIndex + 1}
                              </div>
                              <div className='lesson-info'>
                                {getLessonIcon('video')}
                                <span className={`title ${lesson.is_published ? '' : 'completed'}`}>{lesson.name}</span>
                              </div>
                            </div>
                            <div className='right'>
                              <div className='flex gap-2'>
                                <LessonForm
                                  handleLesson={updateLesson}
                                  courseId={slug}
                                  lesson={lesson}
                                  section={section}
                                />
                                <DeleteLessonModal id={lesson.id} deleteLesson={deleteLesson} />
                              </div>
                              <span className='fs-6 fw-semibold'>15 phút</span>
                            </div>
                          </div>
                      ))}
                      <LessonForm sectionId={section.id} handleLesson={addLesson} />
                    </div>
                  </div>
                }
              />
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}
