import axios from 'axios';

// Create a pre-configured Axios instance. You may adjust baseURL if the app is served from a different sub-directory.
const api = axios.create({
  baseURL: '/',
  withCredentials: true,
  headers: {
    'X-Requested-With': 'XMLHttpRequest',
  },
});

/* -------------------------------------------------------------------------- */
/*                                   Courses                                  */
/* -------------------------------------------------------------------------- */

export const fetchCatalog = (params = {}) => api.get('/course-catalog', { params });
export const enrollCourse = (courseId) => api.post(`/courses/${courseId}/enroll`);
export const fetchLevels = (courseId) => api.get(`/courses/${courseId}/levels`);

/* -------------------------------------------------------------------------- */
/*                                Pre-Test Flow                               */
/* -------------------------------------------------------------------------- */

export const fetchPreTest = (courseId, levelId) =>
  api.get(`/courses/${courseId}/levels/${levelId}/pre-test`);

export const submitPreTest = (courseId, levelId, answers) =>
  api.post(`/courses/${courseId}/levels/${levelId}/pre-test`, { answers });

/* -------------------------------------------------------------------------- */
/*                              Content & Progress                            */
/* -------------------------------------------------------------------------- */

export const fetchContent = (courseId, levelId, contentId) =>
  api.get(`/student/courses/${courseId}/levels/${levelId}/contents/${contentId}`);

export const sendProgress = (contentId, {
  watched_seconds,
  is_completed = false,
}) => api.post(`/contents/${contentId}/progress`, { watched_seconds, is_completed });

/* -------------------------------------------------------------------------- */
/*                                 Review Test                                */
/* -------------------------------------------------------------------------- */

export const takeReview = (courseId, levelId, assessmentId) =>
  api.get(`/student/courses/${courseId}/levels/${levelId}/assessments/${assessmentId}/take`);

export const submitReview = (
  courseId,
  levelId,
  assessmentId,
  attemptId,
  payload,
) =>
  api.post(`/student/courses/${courseId}/levels/${levelId}/assessments/${assessmentId}/submit/${attemptId}`, payload);

/* -------------------------------------------------------------------------- */
/*                              Certificate Flow                              */
/* -------------------------------------------------------------------------- */

export const requestCertificate = (courseId, studentId = '') => {
  // If studentId is optional leave it blank for current auth user
  const target = studentId ? `/courses/${courseId}/certificates/${studentId}` : `/courses/${courseId}/certificates`;
  return api.post(target);
};

export const downloadCertificate = (certificateId) =>
  api.get(`/certificates/${certificateId}/download`, { responseType: 'blob' });

/* -------------------------------------------------------------------------- */
/*                               Axios Helpers                                */
/* -------------------------------------------------------------------------- */

// Optional: attach interceptors to centralize auth or error handling
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      // Redirect to login or dispatch event
      window.location.href = '/login';
    }
    return Promise.reject(error);
  },
);

export default api; 