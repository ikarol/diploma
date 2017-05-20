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
