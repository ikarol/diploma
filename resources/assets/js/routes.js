import VueRouter from 'vue-router';

var routes = [
    {
        path: '/prof-task-list',
        component: require('./components/ProfessorDiplomasTable')
    },
    {
        path: '/prof-requests',
        component: require('./components/ProfessorDiplomasRequests')
    },
    {
        path: '/stud-task-list',
        component: require('./components/StudentDiplomasTable')
    },
    {
        path: '/stud-requests',
        component: require('./components/StudentDiplomasRequests')
    }
];

export default new VueRouter({
    routes: routes,
    linkActiveClass : 'active'
})
