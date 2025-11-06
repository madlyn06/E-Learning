import Modal from '@/components/common/Modal'
import { Button } from '@/components/ui/Button'
import { FeedbackContext } from '@/context/FeedBackContext'
import { useModal } from '@/hooks/useModal'
import { Trash } from 'lucide-react'
import React, { useContext } from 'react'
import { toast } from 'react-toastify'

function DeleteSectionModal({ id, deleteSection }) {
  const { isOpen, open, close } = useModal()
  const { toggleProcessing } = useContext(FeedbackContext)

  const handleDeleteSection = async () => {
    try {
      toggleProcessing()
      await deleteSection(id)
      toast.success('Section deleted successfully')
      close()
    } catch (e) {
      toast.error('Failed to delete section')
    } finally {
      toggleProcessing()
    }
  }

  return (
    <>
      <Trash size={16} color='#000' style={{ cursor: 'pointer' }} onClick={open} />

      <Modal isOpen={isOpen} onClose={close} title='Xác nhận xóa chương'>
        <p>
          Bạn có chắc chắn muốn xóa chương này? Tất cả bài học trong chương cũng sẽ bị xóa. Hành động này không thể hoàn
          tác.
        </p>
        <div className='form-actions'>
          <Button variant='third' icon='' onClick={close} className='px-4 py-1'>
            Hủy
          </Button>

          <Button variant='secondary' icon='' className='px-4 py-1' onClick={handleDeleteSection}>
            Xóa
          </Button>
        </div>
      </Modal>
    </>
  )
}

export default DeleteSectionModal
