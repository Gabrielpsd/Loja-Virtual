export const BASE_URL = 'http://localhost:8000'
export const PRODUTOS = `${BASE_URL}/produtos`
export const PRODUTOSAPI = `${BASE_URL}/api/produtos`
export const PEDIDOS = `${BASE_URL}/pedidos`
export const PEDIDOSAPI = `${BASE_URL}/api/pedidos`
export const FORNECEDORES = `${BASE_URL}/fornecedores`
export const PESSOAS = `${BASE_URL}/clientesfornecedores`
export const PESSOASAPI = `${BASE_URL}/api/clientesfornecedores`
export const MARCAS = `${BASE_URL}/marcas`
export const MARCASAPI = `${BASE_URL}/api/marcas`
export const ENTRADAS = `${BASE_URL}/entradas`
export const ENTRADASAPI = `${BASE_URL}/api/entradas`
export const DASHBOARD = `${BASE_URL}/api/dashboard`

export default { 
    produtos: {
        lista: PRODUTOS,
        detalhe: (idProduto) => `${PRODUTOS}/${idProduto}`,
        inserir: `${PRODUTOSAPI}/inserir`,
        editar: (idProduto) => `${PRODUTOSAPI}/editar/${idProduto}`,
        remover:(idProduto) => `${PRODUTOSAPI}/remover/${idProduto}`
    },
    pedidos: {
        lista: PEDIDOS,
        criar:`${PEDIDOSAPI}/criarpedido`,
        excluir:(pedido)=>`${PEDIDOSAPI}/excluir/${pedido}`,
        detalhe: (pedido) => `${PEDIDOS}/detalhe/${pedido}`,
        remover: (pedido) => `${PEDIDOSAPI}/removerProdutosPedido/${pedido}`,
        adicionar: (pedido) => `${PEDIDOSAPI}/inserirProdutos/${pedido}`,
        editar: (pedido) => `${PEDIDOSAPI}/editarProdutoPedido/${pedido}`,
    },
    marcas: {
        lista: MARCAS,
        detalhe: (idMarca) => `${MARCAS}/editar/${idMarca}`,
        remover: (idMarca) => `${MARCASAPI}/remover/${idMarca}`,
        adicionar: `${MARCASAPI}/inserir`,
        editar: (idMarca) => `${MARCASAPI}/editar/${idMarca}`,
    },
    fornecedores: {
        lista: FORNECEDORES
    },
    pessoas:{
        lista: PESSOAS,
        editaPessoa:(idPessoa)=> `${PESSOASAPI}/editar/${idPessoa}`,
        inserirPessoa: `${PESSOASAPI}/inserir`,
        excluir: (idPessoa)=> `${PESSOASAPI}/excluir/${idPessoa}`,
        ativar: (idPessoa)=> `${PESSOASAPI}/ativar/${idPessoa}` 
    },
    entradas:{
        lista: ENTRADAS,
        excluir: (pedido) => `${ENTRADASAPI}/excluir/${pedido}`,
        criar:`${ENTRADASAPI}/criarpedido`,
        detalhe: (pedido) => `${ENTRADAS}/detalhe/${pedido}`,
        remover: (pedido) => `${ENTRADASAPI}/removerProdutos/${pedido}`,
        adicionar: (pedido) => `${ENTRADASAPI}/inserirProdutos/${pedido}`,
        editar: (pedido) => `${ENTRADASAPI}/editarProdutos/${pedido}`,
    },
    dashboard:{
        vendasPorSexo: `${DASHBOARD}/vendasporsexo`,
        pessoasPorIdade: `${DASHBOARD}/pessoasPorIdade`,
        vendasPorIdade: `${DASHBOARD}/vendasPorIdade`,
        produtospormarca: `${DASHBOARD}/produtospormarca`,
    },
    EXTENCOESVALIDAS: ['XML', 'xml', 'Xml'],
    ROTAARQUIVOS: `${BASE_URL}/api/upload`
    ,
    geraCor(baseColor = "#3498db") {
        // Convert HEX to HSL
        function hexToHsl(hex) {
            let r = parseInt(hex.substring(1, 3), 16) / 255;
            let g = parseInt(hex.substring(3, 5), 16) / 255;
            let b = parseInt(hex.substring(5, 7), 16) / 255;
    
            let max = Math.max(r, g, b), min = Math.min(r, g, b);
            let h, s, l = (max + min) / 2;
    
            if (max === min) {
                h = s = 0; // Achromatic
            } else {
                let d = max - min;
                s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
                switch (max) {
                    case r: h = (g - b) / d + (g < b ? 6 : 0); break;
                    case g: h = (b - r) / d + 2; break;
                    case b: h = (r - g) / d + 4; break;
                }
                h = Math.round(h * 60);
            }
    
            return { h, s, l };
        }
    
        // Convert HSL to HEX
        function hslToHex(h, s, l) {
            s /= 100;
            l /= 100;
            let c = (1 - Math.abs(2 * l - 1)) * s;
            let x = c * (1 - Math.abs((h / 60) % 2 - 1));
            let m = l - c / 2;
            let r, g, b;
    
            if (h < 60) { r = c; g = x; b = 0; }
            else if (h < 120) { r = x; g = c; b = 0; }
            else if (h < 180) { r = 0; g = c; b = x; }
            else if (h < 240) { r = 0; g = x; b = c; }
            else if (h < 300) { r = x; g = 0; b = c; }
            else { r = c; g = 0; b = x; }
    
            r = Math.round((r + m) * 255);
            g = Math.round((g + m) * 255);
            b = Math.round((b + m) * 255);
    
            return `#${((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1).toUpperCase()}`;
        }
    
        // Get base color in HSL
        let { h, s, l } = hexToHsl(baseColor);
    
        // Increase hue variation range (±60 degrees)
        let newHue = (h + Math.floor(Math.random() * 120 - 60) + 360) % 360;
        
        // Vary lightness slightly for contrast
        let newLightness = Math.min(100, Math.max(10, l * 100 + (Math.random() * 20 - 10)));
    
        return hslToHex(newHue, s * 100, newLightness);
    },
    
    customPtLocale: {
        days: ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'],
        daysShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
        months: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        monthsShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
        firstDayOfWeek: 0, // Sunday as the first day (adjust if needed)
        // Add other properties as required by the library
      },

}
