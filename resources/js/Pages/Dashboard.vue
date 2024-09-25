<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">You're logged in!</div>
                    <div class="p-6 text-gray-900 dark:text-gray-100">ESTAMOS DENTRO LA PTMR AHORA DEBEMOS HACER PATRIA</div>
                    <div class="p-6 text-gray-900 dark:text-gray-100">somos la pura honda</div>
                </div>
            </div>
            <div>
                <h1>Dashboard</h1>
                <form @submit.prevent="importCourses">
                    <input type="file" @change="handleFileUpload" />
                    <button type="submit">Importar Cursos</button>
                </form>
                <div v-if="message">{{ message }}</div>
                <table v-if="courses.length">
                    <thead>
                        <tr>
                            <th>Código del Curso</th>
                            <th>Área Curricular</th>
                            <th>Nombre del Curso</th>
                            <th>Ciclo</th>
                            <th>Tipo de Cursos</th>
                            <th>Grupo</th>
                            <th>Turno</th>
                            <th>N° Aula</th>
                            <th>Número Estudiantes</th>
                            <th>Docente</th>
                            <th>Lunes</th>
                            <th>Martes</th>
                            <th>Miércoles</th>
                            <th>Jueves</th>
                            <th>Viernes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="course in courses" :key="course.id">
                            <td>{{ course.codigo_curso }}</td>
                            <td>{{ course.area_curricular }}</td>
                            <td>{{ course.nombre_curso }}</td>
                            <td>{{ course.ciclo }}</td>
                            <td>{{ course.tipo_curso }}</td>
                            <td>{{ course.grupo }}</td>
                            <td>{{ course.turno }}</td>
                            <td>{{ course.numero_aula }}</td>
                            <td>{{ course.numero_estudiantes }}</td>
                            <td>{{ course.docente }}</td>
                            <td>{{ course.lunes }}</td>
                            <td>{{ course.martes }}</td>
                            <td>{{ course.miercoles }}</td>
                            <td>{{ course.jueves }}</td>
                            <td>{{ course.viernes }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    data() {
        return {
            file: null,
            message: '',
            courses: []
        };
    },
    methods: {
        handleFileUpload(event) {
            this.file = event.target.files[0];
        },
        async importCourses() {
            const formData = new FormData();
            formData.append('file', this.file);

            try {
                const response = await this.$http.post('/import', formData);
                this.message = response.data.message;
                this.loadCourses(); // Cargar cursos después de la importación
            } catch (error) {
                this.message = 'Error al importar cursos.';
            }
        },
        async loadCourses() {
            try {
                const response = await this.$http.get('/api/courses');
                this.courses = response.data;
            } catch (error) {
                console.error('Error al cargar cursos:', error);
            }
        }
    },
    created() {
        this.loadCourses(); // Cargar cursos al inicio
    }
};
</script>