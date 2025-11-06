import { Button } from '@/components/ui/Button'
import React, { useState } from 'react'
import Accordions from '@/components/common/Accordions'
import { LESSON_TYPE_ICON } from '@/utils/const'
import { Input } from '@/components/ui/Input'
import Modal from '../../../common/Modal'
import { Controller, useFormContext } from 'react-hook-form'

const chaptersInit = [
  {
    id: 11,
    title: 'Program Information 2023/2024 Edition',
    lesson: [
      { id: 1, name: 'About The Course', type: 1 },
      { id: 2, name: 'About The Course', type: 2 },
      { id: 3, name: 'About The Course', type: 3 },
      { id: 4, name: 'About The Course', type: 1 }
    ]
  }
]
function Curriculum({ tab }) {
  const [isShowModal, setIsShowModal] = useState({
    title: false,
    article: false,
    description: false,
    question: false
  })
  const [chapters, setChapters] = useState(chaptersInit)

  const {
    formState: { errors },
    control
  } = useFormContext()

  const handleAddChapter = () => {
    setChapters([...chapters, { title: 'New Chapter' }])
  }

  const handleCloseForm = () => {
    setIsShowModal({
      article: false,
      description: false,
      question: false
    })
  }

  const handleSubmitArticle = (articleData) => {
    console.log('Article submitted:', articleData)
  }
  return (
    <div
      style={{
        display: tab === 'curriculum' ? 'block' : 'none'
      }}
      className='widget-content-inner'
    >
      <div className='tf-accordion-style-3 tf-accordion'>
        {chapters.map((chapter, index) => (
          <div key={chapter.id}>
            <Accordions
              childrenContent={
                <>
                  {chapter.lesson?.map((lesson) => (
                    <ul className='list' key={lesson.id}>
                      <li className='icon'>
                        <i className={LESSON_TYPE_ICON[lesson.type]} />
                      </li>
                      <li>
                        <span className='text'>{lesson.name}</span>
                      </li>
                      <li className='tf-accordion-btn btn-style-2'>
                        <a href='#' className='btn-edit btn'>
                          <i className='flaticon-edit' />
                        </a>
                        <a href='#' className='btn-remove btn'>
                          <i className='flaticon-close' />
                        </a>
                      </li>
                    </ul>
                  ))}

                  <div className='btn-add'>
                    <Button
                      variant='fifth'
                      className='fs-14 fw-5'
                      onClick={() => setIsShowModal({ ...isShowModal, article: true })}
                    >
                      Add Article
                    </Button>
                    <Button
                      variant='fourth'
                      className='fs-14 fw-5'
                      onClick={() => setIsShowModal({ ...isShowModal, description: true })}
                    >
                      Add Description
                    </Button>
                    <Button
                      variant='third'
                      className='fs-14 fw-5'
                      onClick={() => setIsShowModal({ ...isShowModal, question: true })}
                    >
                      Add Question
                    </Button>
                  </div>
                </>
              }
              childrenHeader={
                <div className='tf-accordion-btn btn-style-2'>
                  <button
                    href='#'
                    className='btn-edit btn'
                    type='button'
                    onClick={() => setIsShowModal({ ...isShowModal, title: true })}
                  >
                    <i className='flaticon-edit' />
                  </button>
                  <button
                    className='btn-remove btn'
                    // onClick={() => setIsEditChapter({ ...isEditChapter, [chapter.id]: false })}
                  >
                    <i className='flaticon-close' />
                  </button>
                </div>
              }
              title={chapter.title}
            />
          </div>
        ))}
      </div>
      <div className='my-4'>
        <Button onClick={() => handleAddChapter()} className='style-fourth fs-14 fw-5'>
          Add Chapter
        </Button>
      </div>

      {/* Modal add article */}
      {isShowModal.article && <Modal onClose={handleCloseForm} onSubmit={handleSubmitArticle}></Modal>}
      {isShowModal.description && <Modal onClose={handleCloseForm} onSubmit={handleSubmitArticle}></Modal>}
      {isShowModal.question && <Modal onClose={handleCloseForm} onSubmit={handleSubmitArticle}></Modal>}
      {isShowModal.title && (
        <Modal title={'Edit chapter name'} onClose={handleCloseForm} onSubmit={handleSubmitArticle}>
          <Controller
            name='section.title'
            control={control}
            render={({ field }) => <Input {...field} label='Chapter name' error={errors.name?.message} />}
          />
          <div className='flex justify-end'>
            <Button variant='primary' type='submit' className='px-3 py-2'>
              Save
            </Button>
          </div>
        </Modal>
      )}
    </div>
  )
}

export default Curriculum
