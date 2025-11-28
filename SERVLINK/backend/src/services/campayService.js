const axios = require('axios');

let cachedToken = null;
let tokenExpiry = 0;

const getConfig = () => ({
  baseUrl: process.env.CAMPAY_BASE_URL || 'https://demo.campay.net/api',
  username: process.env.CAMPAY_USERNAME,
  password: process.env.CAMPAY_PASSWORD
});

const getToken = async () => {
  if (cachedToken && Date.now() < tokenExpiry) {
    return cachedToken;
  }

  const { baseUrl, username, password } = getConfig();

  const response = await axios.post(`${baseUrl}/token/`, {
    username,
    password
  });

  cachedToken = response.data.token;
  tokenExpiry = Date.now() + 3500 * 1000;
  return cachedToken;
};

const initiatePayment = async ({ amount, phone, description, external_reference }) => {
  const { baseUrl } = getConfig();
  const token = await getToken();

  const isDemoMode = baseUrl.includes('demo.campay.net');
  const paymentAmount = isDemoMode ? '10' : String(amount);

  const payload = {
    amount: paymentAmount,
    from: phone,
    description: description || 'Payment',
    external_reference: external_reference || ''
  };

  console.log('[CamPay] Collect request:', JSON.stringify(payload));

  const response = await axios.post(
    `${baseUrl}/collect/`,
    payload,
    {
      headers: { Authorization: `Token ${token}` }
    }
  );

  console.log('[CamPay] Collect response:', JSON.stringify(response.data));
  return response.data;
};

const checkPaymentStatus = async (reference) => {
  const { baseUrl } = getConfig();
  const token = await getToken();

  const response = await axios.get(
    `${baseUrl}/transaction/${reference}/`,
    {
      headers: { Authorization: `Token ${token}` }
    }
  );

  return response.data;
};

const initiateWithdraw = async ({ amount, phone, description, external_reference }) => {
  const { baseUrl } = getConfig();
  const token = await getToken();

  const response = await axios.post(
    `${baseUrl}/withdraw/`,
    {
      amount: String(amount),
      to: phone,
      description: description || 'Withdrawal',
      external_reference: external_reference || ''
    },
    {
      headers: { Authorization: `Token ${token}` }
    }
  );

  return response.data;
};

module.exports = {
  initiatePayment,
  checkPaymentStatus,
  initiateWithdraw
};
