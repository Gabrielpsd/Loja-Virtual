
<script>
    import { Link,usePage } from '@inertiajs/vue3'

    export default{
        components: {
            Link
        },
        data(){
            return{
                userName: usePage().props.auth.user?.name,
            }
        },
        methods: {
            isUrl(urls) {
            
                let currentUrl = usePage()
                currentUrl = currentUrl.url
            
            if (urls.length < 1) {
                return currentUrl === ''
            }
                return urls === currentUrl
            },
        },
    }
</script>

<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/pedidos"><img src="../Assets/logo.png" alt="logopage" width="100" height="100" style="border-radius: 30%;"></a>
        <button
      class="navbar-toggler"
      type="button"
      data-bs-toggle="collapse"
      data-bs-target="#navbarNav"
      aria-controls="navbarNav"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>
        <div class="collapse navbar-collapse rounded" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item">
                <Link class="nav-link" :class="isUrl('/produtos') ? 'ativo' : '' " href="/produtos">Produtos E Serviços</Link>
            </li>
            <li class="nav-item">
                <Link class="nav-link" :class="isUrl('/cliente') ? 'ativo' : '' " href="/cliente">Cliente</Link>
            </li>
            <li class="nav-item">
                <Link class="nav-link" :class="isUrl('/pedidos') ? 'ativo' : '' " href="/pedidos">Pedidos</Link>
            </li>
            </ul>
        </div>
        <div class="welcome-message computer-view">
          Bem-vindo, {{ this.userName}}!
        </div>
    </nav>
    <div class="px-4 py-8 md:flex-1 md:p-12 md:overflow-y-auto" scroll-region>
        <slot></slot>
    </div>
</template>

<style scoped>

nav ul {
  list-style: none;
  padding: 0;
  margin: 0;
  display: flex;
  gap: 2rem;
}

nav li a {
  color: #2c3e50;
  font-size: 1.1rem;
  font-weight: 500;
  text-decoration: none;
  padding: 8px 0;
  position: relative;
  display: inline-block;
}

.welcome-message {
  font-size: 16px;
  font-weight: bold;
  margin-bottom: 20px;
}

nav li a::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 0;
  height: 2px;
  background-color: #e74c3c;
  transition: width 0.3s ease;
}

nav li a:hover::after {
  width: 100%;
}

/* Optional: active state for current page */
nav li a.active {
  color: #007bff;
}

.computer-view{
    display: inline;
}

nav li a.active::after {
  width: 100%;
  background-color: #007bff;
}

@media(min-width: 769px)
{
    .ativo{
        
        font-weight: bold;
        color: #007bff; /* Blue color for active link */
        border-bottom: 2px solid #007bff; /* Underline effect */
    }
}


@media(max-width: 758px)
{
    .computer-view{
        display: none;
    }
    .ativo{
        
        font-weight: bold;
        color: #055ebe; /* Blue color for active link */
    }

}
    img{
        border-radius: 10%;
    }
</style>
