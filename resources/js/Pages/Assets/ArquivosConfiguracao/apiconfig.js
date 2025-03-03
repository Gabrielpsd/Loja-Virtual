export const BASE_URL = 'http://localhost:8000'
export const SERVICOS = `${BASE_URL}/servicos`
export const SERVICOSAPI = `${BASE_URL}/api/servicos`
export const VEICULOS = `${BASE_URL}/veiculos`
export const VEICULOSAPI = `${BASE_URL}/api/veiculos`
export const ORDEMSERVICO = `${BASE_URL}/OrdemServico`
export const ORDEMSERVICOAPI = `${BASE_URL}/api/OrdemServico`
export const PESSOAS = `${BASE_URL}/clientes`
export const PESSOASAPI = `${BASE_URL}/api/clientes`
export const DASHBOARD = `${BASE_URL}/api/dashboard`

export default { 
    servicos: {
        lista: SERVICOS,
        detalhe: (idServico) => `${SERVICOS}/${idServico}`,
        inserir: `${SERVICOSAPI}/inserir`,
        editar: (idServico) => `${SERVICOSAPI}/editar/${idServico}`,
        remover:(idServico) => `${SERVICOSAPI}/remover/${idServico}`
    },
    veiculos: {
        lista: VEICULOS,
        inserir:`${VEICULOSAPI}/inserir`,
        detalhe: (pedido) => `${VEICULOS}/detalhe/${pedido}`,
        editar: (id) => `${VEICULOSAPI}/editar/${id}`,
    },
    ordemServico: {
        lista: ORDEMSERVICO,
        inserir:`${ORDEMSERVICOAPI}/inserir`,
        detalhe: (pedido) => `${ORDEMSERVICO}/detalhe/${pedido}`,
        editar: (id) => `${ORDEMSERVICOAPI}/editar/${id}`,
    },
    pessoas:{
        lista: PESSOAS,
        editaPessoa:(idPessoa)=> `${PESSOASAPI}/editar/${idPessoa}`,
        inserirPessoa: `${PESSOASAPI}/inserir`,
        excluir: (idPessoa)=> `${PESSOASAPI}/excluir/${idPessoa}`,
        ativar: (idPessoa)=> `${PESSOASAPI}/ativar/${idPessoa}` 
    },
    dashboard:{
        vendasPorSexo: `${DASHBOARD}/vendasporsexo`,
        pessoasPorIdade: `${DASHBOARD}/pessoasPorIdade`,
        vendasPorIdade: `${DASHBOARD}/vendasPorIdade`,
        produtospormarca: `${DASHBOARD}/produtospormarca`,
    },
    EXTENCOESVALIDAS: ['XML', 'xml', 'Xml'],
    ROTAARQUIVOS: `${BASE_URL}/api/upload`,
    DIASMAXIMOS : 43800 // equivale a 120 anos 
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

      calculateAge(birthdate) {
        // Parse the birthdate string into a Date object
        const birthDate = new Date(birthdate);
        
        // Get the current date
        const currentDate = new Date();
        
        // Calculate the difference in milliseconds
        const timeDifference = currentDate.getTime() - birthDate.getTime();
        
        // Convert milliseconds to days
        const daysDifference = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
        
        return daysDifference;
      }

}
