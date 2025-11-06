import apiFetch from '@/utils/request'

export const courseApi = {
  getByInstructorId: (token) =>
    apiFetch.get(`/v1/elearning/instructor-courses`, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }),
  create: (body) => apiFetch.post('/v1/elearning/courses', body),
  update: (id, body) => apiFetch.put(`/v1/elearning/courses/${id}`, body),
  remove: (id) => apiFetch.delete(`/v1/elearning/courses/${id}`),
  getOne: (id) => apiFetch.get(`/v1/elearning/courses/${id}`),
  addSection: (courseId, data) => apiFetch.post(`/v1/elearning/courses/${courseId}/sections`, data),
  updateSection: (sectionId, data) => apiFetch.put(`/v1/elearning/sections/${sectionId}`, data),
  removeSection: (sectionId) => apiFetch.delete(`/v1/elearning/sections/${sectionId}`),
  addLesson: (sectionId, data) => apiFetch.post(`/v1/elearning/sections/${sectionId}/lessons`, data),
  updateLesson: (lessonId, data) => apiFetch.post(`/v1/elearning/lessons/${lessonId}`, data),
  removeLesson: (lessonId) => apiFetch.delete(`/v1/elearning/lessons/${lessonId}`),
  uploadVideo: (formData) => apiFetch.post('/v1/elearning/media/upload', formData),
  getSummaryCourses: (token) =>
    apiFetch.get('/v1/elearning/dashboard-courses', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    }),
  getCourseBestSelling: (token) =>
    apiFetch.get('/v1/elearning/best-selling-courses', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
}
