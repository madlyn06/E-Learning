import Modal from '@/components/common/Modal'
import { Button } from '@/components/ui/Button'
import { FeedbackContext } from '@/context/FeedBackContext'
import { useModal } from '@/hooks/useModal'
import { Trash } from 'lucide-react'
import React, { useContext } from 'react'
import { toast } from 'react-toastify'

function DeleteLessonModal({ id, deleteLesson }) {
  const { isOpen, open, close } = useModal()
  const { toggleProcessing } = useContext(FeedbackContext)

  const handleDeleteLesson = async () => {
    try {
      toggleProcessing()
      await deleteLesson(id)
      toast.success('Lesson deleted successfully')
      close()
    } catch (e) {
      toast.error('Failed to delete lesson')
    } finally {
      toggleProcessing()
    }
  }

  return (
    <>
      <Trash size={16} color='#000' style={{ cursor: 'pointer' }} onClick={open} />

      <Modal isOpen={isOpen} onClose={close} title='Xác nhận xóa bài học'>
        <p>Bạn có chắc chắn muốn xóa bài học này? Hành động này không thể hoàn tác.</p>
        <div className='form-actions'>
          <Button variant='third' icon='' onClick={close} className='px-4 py-1'>
            Hủy
          </Button>

          <Button variant='secondary' icon='' className='px-4 py-1' onClick={handleDeleteLesson}>
            Xóa
          </Button>
        </div>
      </Modal>
    </>
  )
}

export default DeleteLessonModal
