import VueRouter from 'vue-router';

var routes = [
    {
        path: '/prof-diploma-list',
        component: require('./components/professor/diploma/list/Table')
    },
    {
        path: '/prof-diploma-requests',
        component: require('./components/professor/diploma/requests/Requests')
    },
    {
        path: '/stud-diploma-list',
        component: require('./components/student/diploma/list/Table')
    },
    {
        path: '/prof-course_project-list',
        component: require('./components/professor/course_project/list/Table')
    },
    {
        path: '/prof-course_project-requests',
        component: require('./components/professor/course_project/requests/Requests')
    },
    {
        path: '/stud-course_project-list',
        component: require('./components/student/course_project/list/Table')
    },
    // {
    //     path: '/:id/prof-diploma-info',
    //     component: require('./components/ProfessorDiplomaInfo'),
    //     props: true,
    // },
];

export default new VueRouter({
    routes: routes,
    linkActiveClass : 'active'
})
