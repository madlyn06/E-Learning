import useSWR from 'swr'
import { fetcher } from '@/utils/fetcher'
import { courseApi } from '@/lib/api/dashboard/courseApi'

export function useCourse(courseId) {
  const { data, error, isLoading, mutate } = useSWR(courseId ? `/v1/elearning/courses/${courseId}` : null, fetcher)

  const updateCourse = async (payload) => {
    await courseApi.update(courseId, payload)
    await mutate()
    return data
  }

  const deleteCourse = async ({ mutateList } = {}) => {
    await courseApi.remove(courseId)
    await mutate()
    if (mutateList) await mutateList() // revalidate đúng page hiện tại
  }

  const addSection = async (courseId, data) => {
    await courseApi.addSection(courseId, data)
    await mutate()
    return data
  }

  const updateSection = async (sectionId, data) => {
    await courseApi.updateSection(sectionId, data)
    await mutate()
    return data
  }

  const deleteSection = async (sectionId) => {
    await courseApi.removeSection(sectionId)
    await mutate()
    return data
  }

  const addLesson = async (sectionId, data) => {
    await courseApi.addLesson(sectionId, data)
    await mutate()
    return data
  }

  const updateLesson = async (lessonId, data) => {
    await courseApi.updateLesson(lessonId, data)
    await mutate()
    return data
  }

  const deleteLesson = async (lessonId) => {
    await courseApi.removeLesson(lessonId)
    await mutate()
    return data
  }

  const uploadVideo = async (formData) => {
    await courseApi.uploadVideo(formData)
    await mutate()
    return data
  }

  return [
    { data: data?.data, error, isLoading, mutate },
    {
      updateCourse,
      deleteCourse,
      addSection,
      updateSection,
      deleteSection,
      addLesson,
      updateLesson,
      deleteLesson,
      uploadVideo
    }
  ]
}
