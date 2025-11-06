import Modal from '@/components/common/Modal'
import { Button } from '@/components/ui/Button'
import { FeedbackContext } from '@/context/FeedBackContext'
import { useModal } from '@/hooks/useModal'
import React, { useContext, useState } from 'react'
import { toast } from 'react-toastify'
import { useTranslation } from 'next-i18next'

function DeleteCourseModal({ id, mutate, deleteCourse }) {
  const { isOpen, open, close } = useModal()
  const { toggleProcessing } = useContext(FeedbackContext)
  const { t } = useTranslation('instructor')
  const handleDeleteCourse = async () => {
    try {
      toggleProcessing()
      await deleteCourse(id)
      await mutate()
      toast.success(t('courseDeletedSuccessfully'))
      close()
    } catch (e) {
      toast.error(t('failedToDeleteCourse'))
    } finally {
      toggleProcessing()
    }
  }

  return (
    <div>
      <Button style={{ padding: '7px' }} onClick={open} variant='fifth' icon='' className='btn-remove btn'>
        <i className='flaticon-close' />
      </Button>
      <Modal isOpen={isOpen} onClose={close} title={t('confirmDeleteCourse')}>
        <p>{t('whenDeletingTheCourseCannotBeRecovered')}</p>
        <div className='form-actions'>
          <Button variant='third' icon='' onClick={close} className='px-4 py-1'>
            {t('cancel')}
          </Button>

          <Button variant='secondary' icon='' className='px-4 py-1' onClick={handleDeleteCourse}>
            {t('delete')}
          </Button>
        </div>
      </Modal>
    </div>
  )
}

export default DeleteCourseModal
