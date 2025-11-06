import { useContext, useEffect, useState } from 'react'
import Modal from '@/components/common/Modal'
import { Button } from '@/components/ui/Button'
import { useModal } from '@/hooks/useModal'
import { Controller, useFieldArray, useForm } from 'react-hook-form'
import { Input } from '@/components/ui/Input'
import { SelectField } from '@/components/ui/MultiSelect'
import { Textarea } from '@/components/ui/Textarea'
import { FeedbackContext } from '@/context/FeedBackContext'
import { toast } from 'react-toastify'
import { LESSON_TYPE, LESSON_TYPE_OPTIONS } from '@/utils/const'
import { Plus, SquarePen } from 'lucide-react'
import RadioButton from '@/components/ui/RadioButton'
import Checkbox from '@/components/ui/Checkbox'
import { useTranslation } from 'next-i18next'

export function LessonForm({ lesson, handleLesson, sectionId }) {
  const { close, isOpen, open } = useModal()
  const { toggleProcessing } = useContext(FeedbackContext)
  const { t } = useTranslation('instructor')
  const [file, setFile] = useState(null)

  const lessonType = lesson ? LESSON_TYPE_OPTIONS.find((type) => type.value === lesson?.type) : LESSON_TYPE_OPTIONS[0]

  const {
    control,
    formState: { errors },
    clearErrors,
    reset,
    handleSubmit,
    watch,
    setError
  } = useForm({
    defaultValues: {
      name: lesson?.name || '',
      type: lessonType,
      summary: lesson?.summary || '',
      content: lesson?.content || '',
      video_id: lesson?.video_id || '',
      is_free: lesson?.is_free || 0,
      is_published: lesson?.is_published || 1,
      display_order: lesson?.display_order || 0,
      correct: lesson?.questions?.[0]?.correct || 0,
      question: lesson?.questions?.[0]?.question || '',
      explanation: lesson?.questions?.[0]?.explanation || '',
      answers: lesson?.questions?.[0]?.choices?.map((choice) => ({ name: choice })) || [{ name: '' }]
    }
  })

  const {
    fields: answerFields,
    append: appendAnswer,
    remove: removeAnswer
  } = useFieldArray({
    control,
    name: 'answers'
  })

  const watchedAnswers = watch('answers')

  const onSubmit = handleSubmit(async (data) => {
    try {
      toggleProcessing()

      const formData = new FormData()
      formData.append('name', data.name)
      formData.append('type', data.type.value)
      formData.append('summary', data.summary)
      formData.append('content', data.content)
      formData.append('is_free', data.is_free ? 1 : 0)
      formData.append('is_published', data.is_published ? 1 : 0)
      formData.append('display_order', data.display_order)

      switch (data.type.value) {
        case LESSON_TYPE.QUIZ:
          const question = {
            question: data.question,
            choices: data.answers.map((answer) => answer.name),
            correct: data.correct,
            explanation: data.explanation
          }

          formData.append('questions', JSON.stringify([question]))
          break
        case LESSON_TYPE.YOUTUBE:
          formData.append('video_id', data.video_id)
          break
        case LESSON_TYPE.FILE:
          formData.append('video_id', data.video_id)
          break
        case LESSON_TYPE.VIDEO:
          formData.append('video', file)
          break
      }

      if (lesson) formData.append('_method', 'PUT')
      const id = lesson ? lesson.id : sectionId
      await handleLesson(id, formData)
      close()
      reset()
      toast.success(lesson ? t('updateLessonSuccess') : t('addLessonSuccess'))
    } catch (error) {
      if (error.status === 422) {
        console.log(Object.keys(error.payload.errors))
        Object.keys(error.payload.errors).forEach((key) => {
          setError(key, { message: error.payload.errors[key][0] })
        })
      }
      toast.error(lesson ? t('updateLessonFailed') : t('addLessonFailed'))
    } finally {
      toggleProcessing()
    }
  })

  const renderLessonType = {
    [LESSON_TYPE.YOUTUBE]: (
      <Controller
        name='video_id'
        control={control}
        rules={{
          required: t('requiredYouTubeVideoId'),
          pattern: {
            value: /^[a-zA-Z0-9_-]{11}$/,
            message: t('invalidYouTubeVideoId')
          }
        }}
        render={({ field }) => <Input {...field} label={t('youTubeVideoId')} error={errors.video_id?.message} />}
      />
    ),
    [LESSON_TYPE.FILE]: (
      <Controller
        name='video_id'
        control={control}
        rules={{ required: t('requiredFile') }}
        render={({ field }) => (
          <Input
            type='file'
            accept='*/*'
            {...field}
            onChange={async (e) => {
              const file = e.target.files?.[0]
              if (!file) return
              field.onChange(file)
              setFile(file)
            }}
            label={t('file')}
            error={errors.video_id?.message}
          />
        )}
      />
    ),
    [LESSON_TYPE.VIDEO]: (
      <Controller
        name='video_id'
        control={control}
        rules={{ required: t('requiredVideo') }}
        render={({ field }) => (
          <Input
            type='file'
            accept='video/*'
            {...field}
            onChange={async (e) => {
              const file = e.target.files?.[0]
              if (!file) return
              field.onChange(file)
              setFile(file)
            }}
            label={t('videoUrl')}
            error={errors.video_id?.message}
          />
        )}
      />
    ),
    [LESSON_TYPE.QUIZ]: (
      <div className='flex flex-column gap-4 w-full'>
        <Controller
          name='question'
          control={control}
          rules={{ required: t('requiredQuestion') }}
          render={({ field }) => <Input {...field} label={t('question')} error={errors.question?.message} />}
        />

        {answerFields.map((field, index) => (
          <div key={field.id} className='flex gap-2 items-start w-full'>
            <Controller
              name={`answers.${index}.name`}
              control={control}
              rules={{ required: t('requiredAnswer') }}
              render={({ field }) => (
                <Input
                  {...field}
                  label={index === 0 ? t('answer') : undefined}
                  error={errors.answers?.[index]?.name?.message}
                />
              )}
            />
            {answerFields.length > 1 && (
              <Button
                variant='fifth'
                className='px-3 py-1 fs-5 flex-shrink-0 align-self-end'
                icon=''
                type='button'
                onClick={() => removeAnswer(index)}
              >
                {t('delete')}
              </Button>
            )}
          </div>
        ))}

        <div>
          <Button
            variant='primary'
            className='px-3 py-1 fs-5 mt-2'
            type='button'
            icon=''
            onClick={() => {
              appendAnswer({ name: '' })
            }}
          >
            <Plus size={16} />
            {t('addAnswer')}
          </Button>
        </div>

        <Controller
          name='correct'
          control={control}
          rules={{ required: t('requiredCorrectAnswer') }}
          render={({ field }) => {
            return (
              <RadioButton
                {...field}
                options={watchedAnswers.map((answer, index) => ({
                  label: answer.name || `${t('answer')} ${index + 1}`,
                  value: index
                }))}
                label={t('correctAnswer')}
                error={errors.correct?.message}
              />
            )
          }}
        />

        <Controller
          name='explanation'
          control={control}
          render={({ field }) => <Input {...field} label={t('explanation')} error={errors.explanation?.message} />}
        />
      </div>
    )
  }

  useEffect(() => {
    if (lesson && isOpen) {
      reset({
        name: lesson?.name,
        type: lessonType,
        summary: lesson?.summary,
        content: lesson?.content,
        video_url: lesson?.video_url,
        is_free: lesson?.is_free,
        is_published: lesson?.is_published,
        display_order: lesson?.display_order,
        correct: lesson?.questions?.[0]?.correct,
        question: lesson?.questions?.[0]?.question,
        explanation: lesson?.questions?.[0]?.explanation,
        answers: lesson?.questions?.[0]?.choices?.map((choice) => ({ name: choice })) || [{ name: '' }]
      })
    }
  }, [lesson, reset, lessonType, isOpen])

  return (
    <>
      {lesson ? (
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
        <Button variant='primary' className='px-4 fs-4 py-1 mt-2' icon='' onClick={open}>
          <Plus size={16} />
          {t('addLesson')}
        </Button>
      )}

      <Modal isOpen={isOpen} onClose={close} title={t('addLesson')}>
        <form onSubmit={onSubmit} className='flex flex-column gap-5'>
          <Controller
            name='name'
            control={control}
            rules={{ required: t('requiredLessonName') }}
            render={({ field }) => <Input {...field} label={t('lessonName')} error={errors.name?.message} />}
          />

          <Controller
            name='summary'
            control={control}
            render={({ field }) => <Textarea {...field} label={t('summary')} error={errors.summary?.message} />}
          />

          <Controller
            name='content'
            control={control}
            render={({ field }) => <Textarea {...field} label={t('content')} error={errors.content?.message} />}
          />

          <label>{t('lessonType')}</label>
          <Controller
            name='type'
            control={control}
            rules={{ required: t('requiredLessonType') }}
            render={({ field }) => (
              <SelectField
                {...field}
                onChange={(e) => {
                  field.onChange(e)
                  clearErrors('video_id')
                }}
                options={LESSON_TYPE_OPTIONS}
                label={t('lessonType')}
                error={errors.type?.message}
              />
            )}
          />

          {renderLessonType[watch('type').value]}

          <Controller
            name='display_order'
            control={control}
            rules={{ required: t('requiredDisplayOrder') }}
            render={({ field }) => <Input {...field} label={t('displayOrder')} error={errors.display_order?.message} />}
          />

          <Controller
            name='is_free'
            control={control}
            render={({ field }) => <Checkbox {...field} label={t('free')} error={errors.is_free?.message} />}
          />

          <Controller
            name='is_published'
            control={control}
            render={({ field }) => <Checkbox {...field} label={t('published')} error={errors.is_published?.message} />}
          />
          <div className='form-actions'>
            <Button variant='third' icon='' onClick={close} className='px-4 py-1'>
              {t('cancel')}
            </Button>
            <Button type='submit' variant='primary' className='px-4 fs-4 py-1' icon=''>
              {lesson ? t('updateLesson') : t('addLesson')}
            </Button>
          </div>
        </form>
      </Modal>
    </>
  )
}
