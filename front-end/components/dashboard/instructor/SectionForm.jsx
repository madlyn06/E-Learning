import Modal from '@/components/common/Modal'
import { Button } from '@/components/ui/Button'
import Checkbox from '@/components/ui/Checkbox'
import { Input } from '@/components/ui/Input'
import { Textarea } from '@/components/ui/Textarea'
import { FeedbackContext } from '@/context/FeedBackContext'
import { useModal } from '@/hooks/useModal'
import { Plus, SquarePen } from 'lucide-react'
import { useContext, useEffect } from 'react'
import { Controller, useForm } from 'react-hook-form'
import { toast } from 'react-toastify'
import { useTranslation } from 'next-i18next'

export function SectionForm({ courseId, handleSection, section }) {
  const { isOpen, open, close } = useModal()
  const { toggleProcessing } = useContext(FeedbackContext)
  const { t } = useTranslation('instructor')
  const {
    control,
    formState: { errors },
    reset,
    handleSubmit
  } = useForm({
    defaultValues: {
      name: section?.name || '',
      description: section?.description || '',
      display_order: section?.display_order || 0,
      is_active: section?.is_active || 1
    }
  })

  useEffect(() => {
    if (section && isOpen) {
      reset({
        name: section.name,
        description: section.description,
        display_order: section.display_order,
        is_active: section.is_active
      })
    }
  }, [section, isOpen, reset])

  const onSubmit = handleSubmit(async (data) => {
    try {
      toggleProcessing()
      const body = {
        name: data.name,
        description: data.description,
        display_order: data.display_order,
        is_active: data.is_active
      }
      const id = section ? section.id : courseId
      await handleSection(id, body)
      close()
      reset()
      toast.success(section ? t('updateSectionSuccess') : t('addSectionSuccess'))
    } catch (error) {
      toast.error(section ? t('updateSectionFailed') : t('addSectionFailed'))
    } finally {
      toggleProcessing()
    }
  })

  return (
    <>
      {section ? (
        <SquarePen
          size={16}
          color='#000'
          style={{ cursor: 'pointer', userSelect: 'none' }}
          onClick={(e) => {
            e.stopPropagation()
            open()
          }}
        />
      ) : (
        <Button variant='primary' className='px-4 fs-4 py-1' icon='' onClick={open}>
          <Plus size={16} />
          {t('addChapter')}
        </Button>
      )}

      <Modal isOpen={isOpen} onClose={close} title={section ? t('editChapter') : t('addChapter')}>
        <form onSubmit={onSubmit} className='chapter-form-form flex flex-column gap-4'>
          <Controller
            name='name'
            control={control}
            rules={{ required: t('requiredChapterName') }}
            render={({ field }) => <Input {...field} label={t('chapterName')} error={errors.name?.message} />}
          />

          <Controller
            name='description'
            control={control}
            rules={{ required: t('requiredChapterDescription') }}
            render={({ field }) => (
              <Textarea {...field} label={t('chapterDescription')} error={errors.description?.message} />
            )}
          />

          <Controller
            name='display_order'
            control={control}
            rules={{ required: t('requiredChapterDisplayOrder') }}
            render={({ field }) => (
              <Input {...field} label={t('chapterDisplayOrder')} error={errors.display_order?.message} />
            )}
          />

          <Controller
            name='is_active'
            control={control}
            render={({ field }) => (
              <Checkbox {...field} label={t('chapterIsActive')} error={errors.is_active?.message} />
            )}
          />

          <div className='form-actions'>
            <Button variant='third' icon='' onClick={close} className='px-4 py-1'>
              {t('cancel')}
            </Button>
            <Button type='submit' variant='primary' className='px-4 fs-4 py-1' icon=''>
              {section ? t('updateChapter') : t('addChapter')}
            </Button>
          </div>
        </form>
      </Modal>
    </>
  )
}
